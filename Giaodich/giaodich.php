<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BayGorn1 - Mua game</title>
  <link rel="stylesheet" href="giaodich.css">
  <link rel="stylesheet" href="../header/header.css">
  <link rel="stylesheet" href="../footer/footer.css">
</head>
<body>
    <?php include '../header/header.html'; ?>
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
          echo "</div>";
        } else {
          echo "<p>Không tìm thấy game</p>";
        }

        mysqli_close($conn);
      ?>
    </div>
  </div>
  <?php include '../footer/footer.html'; ?>
</body>
</html>
