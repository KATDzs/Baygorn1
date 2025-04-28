<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameZone - Mua game</title>
  <link rel="stylesheet" href="../asset/css/styles.css">
</head>
<body>
  <div class="container">
    <div class="game-detail">
      <?php
        // Kết nối database
        $conn = mysqli_connect("localhost", "root", "", "gamezone");
        
        // Kiểm tra kết nối
        if (!$conn) {
          die("Kết nối thất bại: " . mysqli_connect_error());
        }

        // Lấy game_id từ URL parameter
        $game_id = isset($_GET['id']) ? $_GET['id'] : 1;

        // Query lấy thông tin game
        $sql = "SELECT * FROM games WHERE game_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $game_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($game = mysqli_fetch_assoc($result)) {
          echo "<img src='{$game['image_url']}' alt='{$game['title']}' class='game-image'>";
          echo "<div class='game-info'>";
          echo "<h1>{$game['title']}</h1>";
          echo "<p class='game-description'>{$game['description']}</p>";
          echo "<p class='game-price'>Giá: " . number_format($game['price'], 0, ',', '.') . " VNĐ</p>";
          echo "<button onclick=\"showTransactionForm('{$game['title']}', '{$game['price']}')\">Mua ngay</button>";
          echo "</div>";
        } else {
          echo "<p>Không tìm thấy game</p>";
        }

        mysqli_close($conn);
      ?>
    </div>

    <div id="transactionForm" class="transaction-form">
      <h2>Thông tin giao dịch</h2>
      <form id="paymentForm">
        <div class="form-group">
          <label for="gameName">Tên game:</label>
          <input type="text" id="gameName" name="gameName" readonly>
        </div>
        <div class="form-group">
          <label for="gamePrice">Giá:</label>
          <input type="text" id="gamePrice" name="gamePrice" readonly>
        </div>
        <div class="form-group">
          <label for="fullName">Họ và tên:</label>
          <input type="text" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="phone">Số điện thoại:</label>
          <input type="tel" id="phone" name="phone" required>
        </div>
        <div class="form-group">
          <label for="paymentMethod">Phương thức thanh toán:</label>
          <select id="paymentMethod" name="paymentMethod" required>
            <option value="">Chọn phương thức thanh toán</option>
            <option value="bank">Chuyển khoản ngân hàng</option>
            <option value="momo">Ví điện tử Momo</option>
            <option value="zalopay">Ví điện tử ZaloPay</option>
          </select>
        </div>
        <button type="submit" class="btn-submit">Xác nhận thanh toán</button>
      </form>
    </div>
  </div>

  <script>
    function showTransactionForm(gameName, gamePrice) {
      document.getElementById('transactionForm').classList.add('active');
      document.getElementById('gameName').value = gameName;
      document.getElementById('gamePrice').value = gamePrice + ' VNĐ';
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Cảm ơn bạn đã mua hàng! Chúng tôi sẽ liên hệ với bạn sớm.');
      document.getElementById('transactionForm').classList.remove('active');
      this.reset();
    });
  </script>
</body>
</html>
