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
        $query = "INSERT INTO games (title, description, detail_desc, platform, price, image_url, created_at, updated_at, modified_by) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            echo '<b>SQL ERROR (prepare addGame):</b> ' . mysqli_error($this->conn);
            error_log('SQL ERROR: ' . mysqli_error($this->conn));
            return 0;
        }
        mysqli_stmt_bind_param($stmt, "ssssdsi", 
            $data['title'],
            $data['description'],
            $data['detail_desc'],
            $data['platform'],
            $data['price'],
            $data['image_url'],
            $data['modified_by']
        );
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            echo '<b>SQL EXECUTE ERROR (addGame):</b> ' . mysqli_error($this->conn);
            error_log('SQL EXECUTE ERROR: ' . mysqli_error($this->conn));
        }
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
                 detail_desc = ?,
                 platform = ?,
                 price = ?,
                 image_url = ?,
                 status = ?,
                 meta = ?,
                 modified_by = ?,
                 updated_at = NOW()
                 WHERE game_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            error_log('SQL ERROR (prepare updateGame): ' . mysqli_error($this->conn));
            return false;
        }
        $title = (string)$data['title'];
        $description = (string)$data['description'];
        $detail_desc = (string)$data['detail_desc'];
        $platform = (string)$data['platform'];
        $price = (float)$data['price'];
        $image_url = (string)$data['image_url'];
        $status = isset($data['status']) ? (string)$data['status'] : 'active';
        $meta = isset($data['meta']) ? (is_array($data['meta']) ? json_encode($data['meta']) : (string)$data['meta']) : '{}';
        $modified_by = (int)$data['modified_by'];
        $game_id = (int)$id;
        mysqli_stmt_bind_param($stmt, "ssssdsssii",
            $title,
            $description,
            $detail_desc,
            $platform,
            $price,
            $image_url,
            $status,
            $meta,
            $modified_by,
            $game_id
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
        mysqli_begin_transaction($this->conn);
        try {
            // Xóa các bản ghi liên quan trong order_details
            $queryOrderDetails = "DELETE FROM order_details WHERE game_id = ?";
            $stmtOrderDetails = mysqli_prepare($this->conn, $queryOrderDetails);
            mysqli_stmt_bind_param($stmtOrderDetails, "i", $id);
            mysqli_stmt_execute($stmtOrderDetails);
            mysqli_stmt_close($stmtOrderDetails);

            // Xóa các bản ghi liên quan trong history
            $queryHistory = "DELETE FROM history WHERE game_id = ?";
            $stmtHistory = mysqli_prepare($this->conn, $queryHistory);
            mysqli_stmt_bind_param($stmtHistory, "i", $id);
            mysqli_stmt_execute($stmtHistory);
            mysqli_stmt_close($stmtHistory);

            // Xóa các bản ghi liên quan trong cart_items
            $queryCartItems = "DELETE FROM cart_items WHERE game_id = ?";
            $stmtCartItems = mysqli_prepare($this->conn, $queryCartItems);
            mysqli_stmt_bind_param($stmtCartItems, "i", $id);
            mysqli_stmt_execute($stmtCartItems);
            mysqli_stmt_close($stmtCartItems);

            // Xóa các bản ghi liên quan trong game_categories
            $query1 = "DELETE FROM game_categories WHERE game_id = ?";
            $stmt1 = mysqli_prepare($this->conn, $query1);
            mysqli_stmt_bind_param($stmt1, "i", $id);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);

            // Xóa game
            $query2 = "DELETE FROM games WHERE game_id = ?";
            $stmt2 = mysqli_prepare($this->conn, $query2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            $success = mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            mysqli_commit($this->conn);
            return $success;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    // Thêm category cho game
    public function addGameCategory($gameId, $categoryId, $modifiedBy) {
        $query = "INSERT INTO game_categories (game_id, category_id, modified_by, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            error_log('SQL ERROR (addGameCategory): ' . mysqli_error($this->conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt, "iii", $gameId, $categoryId, $modifiedBy);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            error_log('SQL EXECUTE ERROR (addGameCategory): ' . mysqli_error($this->conn));
        }
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
}
?>