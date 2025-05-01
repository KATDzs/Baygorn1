<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BayGorn1 - Mua game</title>
  <link rel="stylesheet" href="/Baygorn1/asset/css/styles.css">
  <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
  <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
</head>
<body>
    <?php 
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
    include ROOT_PATH . '/view/layout/header.php'; 
    ?>
  <div class="container">
    <div class="game-detail">
      <?php
        // Kết nối database
        require_once ROOT_PATH . '/core/db_connection.php';
        global $conn;
        
        // Lấy game_id từ URL parameter
        $game_id = isset($_GET['id']) ? $_GET['id'] : 1;

        // Query lấy thông tin game
        $stmt = $conn->prepare("SELECT * FROM games WHERE game_id = ?");
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($game = $result->fetch_assoc()) {
          echo "<img src='/Baygorn1/{$game['image_url']}' alt='{$game['title']}' class='game-image'>";
          echo "<div class='game-info'>";
          echo "<h1>{$game['title']}</h1>";
          echo "<p class='game-description'>{$game['description']}</p>";
          echo "<p class='game-price'>Giá: " . number_format($game['price'], 0, ',', '.') . " VNĐ</p>";
          echo "</div>";
        } else {
          echo "<p>Không tìm thấy game</p>";
        }
        
        $stmt->close();
      ?>
    </div>
  </div>
  <?php include ROOT_PATH . '/view/layout/footer.php'; ?>
</body>
</html>