<?php
class GameModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy tất cả game với phân trang
    public function getAllGames($limit = 12, $offset = 0) {
        $query = "SELECT * FROM games ORDER BY created_at DESC LIMIT ?, ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $games = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $games[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $games;
    }

    // Lấy tổng số game
    public function getTotalGames() {
        $query = "SELECT COUNT(*) as total FROM games";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    // Lấy game theo ID
    public function getGameById($id) {
        $query = "SELECT * FROM games WHERE game_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $game = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $game;
    }

    // Lấy game mới nhất
    public function getLatestGames($limit = 8) {
        $query = "SELECT * FROM games ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $games = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $games[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $games;
    }

    // Lấy game liên quan
    public function getRelatedGames($gameId, $limit = 4) {
        $query = "SELECT g.* FROM games g
                 INNER JOIN game_categories gc1 ON g.game_id = gc1.game_id
                 WHERE gc1.category_id IN (
                     SELECT category_id FROM game_categories WHERE game_id = ?
                 )
                 AND g.game_id != ?
                 GROUP BY g.game_id
                 ORDER BY RAND()
                 LIMIT ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $gameId, $gameId, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $games = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $games[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $games;
    }

    // Lấy game bán chạy nhất
    public function getTopSellingGames($limit = 5) {
        $query = "SELECT g.*, COUNT(od.order_detail_id) as total_sales 
                 FROM games g
                 LEFT JOIN order_details od ON g.game_id = od.game_id
                 GROUP BY g.game_id
                 ORDER BY total_sales DESC
                 LIMIT ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $games = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $games[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $games;
    }

    // Thêm game mới
    public function addGame($data) {
        $query = "INSERT INTO games (title, description, price, stock, image_url, created_by, created_at, modified_by, modified_at) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, NOW())";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssdiiss", 
            $data['title'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['created_by'],
            $data['modified_by']
        );
        
        $success = mysqli_stmt_execute($stmt);
        $gameId = $success ? mysqli_insert_id($this->conn) : 0;
        mysqli_stmt_close($stmt);
        
        if ($success && isset($data['categories'])) {
            $this->updateGameCategories($gameId, $data['categories'], $data['modified_by']);
        }
        
        return $gameId;
    }

    // Cập nhật game
    public function updateGame($id, $data) {
        $query = "UPDATE games SET 
                 title = ?,
                 description = ?,
                 price = ?,
                 stock = ?,
                 image_url = ?,
                 modified_by = ?,
                 modified_at = NOW()
                 WHERE game_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssdiisi",
            $data['title'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'],
            $data['modified_by'],
            $id
        );
        
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        if ($success && isset($data['categories'])) {
            $this->updateGameCategories($id, $data['categories'], $data['modified_by']);
        }
        
        return $success;
    }

    // Xóa game
    public function deleteGame($id) {
        // Start transaction
        mysqli_begin_transaction($this->conn);
        
        try {
            // Delete game categories first
            $query1 = "DELETE FROM game_categories WHERE game_id = ?";
            $stmt1 = mysqli_prepare($this->conn, $query1);
            mysqli_stmt_bind_param($stmt1, "i", $id);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);
            
            // Then delete the game
            $query2 = "DELETE FROM games WHERE game_id = ?";
            $stmt2 = mysqli_prepare($this->conn, $query2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            $success = mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            // If everything is successful, commit the transaction
            mysqli_commit($this->conn);
            return $success;
            
        } catch (Exception $e) {
            // If there's an error, rollback the transaction
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    // Thêm category cho game
    public function addGameCategory($gameId, $categoryId, $modifiedBy) {
        $query = "INSERT INTO game_categories (game_id, category_id, created_by, created_at) 
                 VALUES (?, ?, ?, NOW())";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iis", $gameId, $categoryId, $modifiedBy);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Cập nhật categories của game
    public function updateGameCategories($gameId, $categories, $modifiedBy) {
        // Start transaction
        mysqli_begin_transaction($this->conn);
        
        try {
            // Delete existing categories
            $query1 = "DELETE FROM game_categories WHERE game_id = ?";
            $stmt1 = mysqli_prepare($this->conn, $query1);
            mysqli_stmt_bind_param($stmt1, "i", $gameId);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);
            
            // Add new categories
            foreach ($categories as $categoryId) {
                $this->addGameCategory($gameId, $categoryId, $modifiedBy);
            }
            
            // If everything is successful, commit the transaction
            mysqli_commit($this->conn);
            return true;
            
        } catch (Exception $e) {
            // If there's an error, rollback the transaction
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    // Cập nhật số lượng game
    public function updateStock($gameId, $quantity) {
        $query = "UPDATE games SET stock = stock + ? WHERE game_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $quantity, $gameId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}
?>