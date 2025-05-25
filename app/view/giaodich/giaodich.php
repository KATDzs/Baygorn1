<?php
session_start();
if (!defined('APP_ROOT')) {
    define('APP_ROOT', realpath(__DIR__ . '/../../../'));
}
$css_files = ['giaodich', 'header', 'footer'];
require_once APP_ROOT . '/app/view/layout/header.php';

require_once APP_ROOT . '/app/Controller/GiaoDichController.php';
require_once APP_ROOT . '/core/db_connection.php';
require_once APP_ROOT . '/app/view/helpers.php';

$giaoDichController = new GiaoDichController($conn);
$game = $giaoDichController->getGameDetail($_GET['id'] ?? 1);
?>
<div class="container">
    <div class="game-detail">
      <?php if ($game): ?>
        <img src="/Baygorn1/asset/img/<?php echo $game['image_url']; ?>" alt="<?php echo $game['title']; ?>" class="game-image">
        <div class="game-info">
          <h1><?php echo $game['title']; ?></h1>
          <p class="game-description"><?php echo $game['description']; ?></p>
          <p class="game-price">Giá: <?php echo format_price($game['price']); ?></p>
          <div class="buttons">
            <a href="/Baygorn1/index.php?url=cart" class="btn-buy">MUA NGAY</a>
            <a href="/Baygorn1/index.php?url=cart" class="btn-preorder">ĐẶT HÀNG</a>
          </div>
        </div>
      <?php else: ?>
        <p>Không tìm thấy game</p>
      <?php endif; ?>
    </div>
</div>
<?php include APP_ROOT . '/app/view/layout/footer.php'; ?>

<?php if ($game): // Only include script if game data is available ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buyNowBtn = document.querySelector('.btn-buy');
    const preOrderBtn = document.querySelector('.btn-preorder');
    const gameId = <?php echo json_encode($game['game_id']); ?>; // Pass game_id from PHP to JS
    const gamePrice = <?php echo json_encode($game['price']); ?>; // Pass game_price from PHP to JS

    // Function to add game to cart via AJAX and then redirect
    function addToCartAndRedirect(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Check if the game is free
        if (parseFloat(gamePrice) === 0) {
            // Handle free game purchase
            console.log('Attempting to purchase free game:', gameId);
            fetch('/Baygorn1/index.php?url=order/purchaseFree', { // New endpoint for free games
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `game_id=${gameId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    showNotification('Đã mua game miễn phí!');
                    // Optionally change button text or hide buttons
                    if (buyNowBtn) buyNowBtn.textContent = 'ĐÃ MUA';
                    if (preOrderBtn) preOrderBtn.style.display = 'none'; // Hide pre-order for purchased free game
                } else {
                    showNotification('Lỗi khi mua game miễn phí: ' + data.message);
                }
                // Do not redirect for free games
            })
            .catch(error => {
                console.error('Error purchasing free game:', error);
                showNotification('Đã xảy ra lỗi khi mua game miễn phí.');
            });

        } else {
            // Handle paid game - existing logic
            console.log('Attempting to add paid game to cart:', gameId);
            fetch('/Baygorn1/index.php?url=cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `game_id=${gameId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                // You might want to show a toast notification here if desired
                console.log('Add to cart response:', data);
                // Redirect to the cart page after attempt to add to cart
                window.location.href = '/Baygorn1/index.php?url=cart';
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                // Still redirect to cart even on error to show potential issues
                window.location.href = '/Baygorn1/index.php?url=cart';
            });
        }
    }

    // Simple notification function (replace with toast if available/preferred)
    function showNotification(message) {
        alert(message); // Using alert for simplicity
        // TODO: Integrate with a proper toast notification system if available
    }

    // Add event listeners to both buttons
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', addToCartAndRedirect);
    }

    if (preOrderBtn) {
        preOrderBtn.addEventListener('click', addToCartAndRedirect);
    }
});
</script>
<?php endif; ?>