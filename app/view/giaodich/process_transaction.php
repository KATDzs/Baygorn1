<?php
// Đã có session_start ở controller, không cần gọi lại ở đây
require_once APP_ROOT . '/app/view/helpers.php';
global $conn;

$isLoggedIn = isset($_SESSION['user_id']);
$userData = [
    'fullName' => '',
    'email' => '',
    'phone' => ''
];

// Nếu đã đăng nhập, lấy thông tin user từ database
if ($isLoggedIn) {
    require_once BASE_PATH . '/app/model/UserModel.php';
    $userModel = new UserModel($conn);
    $user = $userModel->getUserById($_SESSION['user_id']);
    if ($user) {
        $userData['fullName'] = $user['full_name'] ?? '';
        $userData['email'] = $user['email'] ?? '';
        $userData['phone'] = $user['phone'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán - BayGorn1</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
</head>
<body>
    <?php include BASE_PATH . '/app/view/layout/header.php'; ?>
    <div class="confirmation-page">
        <div class="confirmation-content">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($cartItems)): ?>
                <h2>Thông tin đơn hàng</h2>
                <div class="cart-list">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="game-detail" style="display:flex;align-items:center;margin-bottom:18px;">
                            <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="game-image" style="width:100px;height:100px;object-fit:cover;margin-right:18px;">
                            <div class="game-info">
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                <p class="game-description">Số lượng: <?php echo (int)$item['quantity']; ?></p>
                                <p class="game-price">Giá: <?php echo format_price($item['price']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="cart-total-modern" style="font-size:1.2rem;font-weight:bold;margin-bottom:18px;">Tổng cộng: <span class="cart-total-amount"><?php echo format_price($total); ?></span></div>
                </div>
                <div class="confirmation-container">
                    <h2>Điền thông tin để thanh toán</h2>
                    <form id="paymentForm" method="post" action="">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="fullName" required value="<?php echo htmlspecialchars($userData['fullName']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($userData['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" required value="<?php echo htmlspecialchars($userData['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Phương thức thanh toán</label>
                            <select name="paymentMethod" required>
                                <option value="bank">Chuyển khoản ngân hàng</option>
                                <option value="momo">Momo</option>
                                <option value="cod">Thanh toán khi nhận hàng</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-buy" id="confirmPaymentBtn">XÁC NHẬN THANH TOÁN</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Giỏ hàng của bạn đang trống.</p>
            <?php endif; ?>
            <a href="/Baygorn1/" class="btn-preorder">Quay lại trang chủ</a>
        </div>
    </div>

    <!-- The Modal Structure -->
    <div id="qrModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Quét mã QR để thanh toán</h2>
        <img id="qrCodeImage" src="/Baygorn1/asset/img/qr.png" alt="QR Code" style="width: 200px; height: 200px; margin: 20px auto; display: block;">
      </div>
    </div>

    <style>
    /* Modal (background) */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1000; /* Sit on top */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
      padding-top: 60px;
    }

    /* Modal Content/Box */
    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%; /* Could be more or less, depending on screen size */
      max-width: 500px;
      border-radius: 10px;
      text-align: center;
    }

    /* The Close Button */
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    /* Style for the modal title */
    .modal-content h2.modal-title {
      font-size: 1.8rem; /* Adjust size as needed */
      font-weight: 800; /* Match website's bold titles */
      margin-top: 0;
      margin-bottom: 1.5rem; /* Add some space below the title */
      background: linear-gradient(45deg, #FF4655, #FF8F9C); /* Use a similar gradient */
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      color: transparent; /* Fallback for browsers that don't support gradients */
    }
    </style>

    <script>
    // Get the modal
    var modal = document.getElementById("qrModal");

    // Get the button that opens the modal
    var btn = document.getElementById("confirmPaymentBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // Get the QR code image element
    var qrImage = document.getElementById("qrCodeImage");

    // When the user clicks the button, open the modal 
    btn.onclick = function(event) {
      event.preventDefault(); // Prevent default form submission
      
      // TODO: Set the actual QR code image source here
      qrImage.src = "/Baygorn1/asset/img/qr.png";
      
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    // Add click event listener to QR code image
    qrImage.onclick = function() {
      // Send AJAX request to simulate payment
      fetch('/Baygorn1/index.php?url=cart/checkout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(new FormData(document.getElementById('paymentForm')))
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          alert('Thanh toán thành công!');
          // Close the modal
          modal.style.display = "none";
          // Redirect to home page
          window.location.href = '/Baygorn1/';
        } else {
          alert('Thanh toán thất bại: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi xử lý thanh toán');
      });
    }
    </script>

    <?php include BASE_PATH . '/app/view/layout/footer.php'; ?>
</body>
</html>