<?php
class HistoryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllHistory($limit = 10, $offset = 0) {
        $query = "SELECT h.*, u.username, g.title as game_title 
                 FROM history h
                 JOIN users u ON h.user_id = u.user_id
                 JOIN games g ON h.game_id = g.game_id
                 ORDER BY h.created_at DESC 
                 LIMIT ?, ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $history = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $history;
    }

    public function getTotalHistory() {
        $query = "SELECT COUNT(*) as total FROM history";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function getUserHistory($userId, $limit = 10, $offset = 0) {
        $query = "SELECT h.*, g.title as game_title, g.image_url 
                 FROM history h
                 JOIN games g ON h.game_id = g.game_id
                 WHERE h.user_id = ?
                 ORDER BY h.created_at DESC 
                 LIMIT ?, ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $userId, $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $history = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $history;
    }

    public function getGameHistory($gameId, $limit = 10, $offset = 0) {
        $query = "SELECT h.*, u.username 
                 FROM history h
                 JOIN users u ON h.user_id = u.user_id
                 WHERE h.game_id = ?
                 ORDER BY h.created_at DESC 
                 LIMIT ?, ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $gameId, $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $history = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $history;
    }

    public function addHistory($userId, $gameId, $action, $details = null) {
        $query = "INSERT INTO history (user_id, game_id, action, details, created_at) 
                 VALUES (?, ?, ?, ?, NOW())";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iiss", $userId, $gameId, $action, $details);
        $success = mysqli_stmt_execute($stmt);
        $historyId = $success ? mysqli_insert_id($this->conn) : 0;
        mysqli_stmt_close($stmt);
        return $historyId;
    }

    public function deleteHistory($id) {
        $query = "DELETE FROM history WHERE history_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function clearUserHistory($userId) {
        $query = "DELETE FROM history WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function addToHistory($user_id, $game_id, $order_id, $quantity, $price) {
        $purchased_at = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("
            INSERT INTO history (user_id, game_id, order_id, quantity, price, purchased_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiiids", $user_id, $game_id, $order_id, $quantity, $price, $purchased_at);
        return $stmt->execute();
    }

    public function getOrderHistory($order_id) {
        $stmt = $this->conn->prepare("
            SELECT h.*, g.title, g.image_url, u.username
            FROM history h
            JOIN games g ON h.game_id = g.game_id
            JOIN users u ON h.user_id = u.user_id
            WHERE h.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalPurchases($user_id) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total, SUM(quantity) as total_items, SUM(quantity * price) as total_spent
            FROM history
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getPurchaseStats($user_id) {
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(DISTINCT game_id) as total_games,
                SUM(quantity) as total_items,
                SUM(quantity * price) as total_spent,
                AVG(price) as average_price,
                MAX(purchased_at) as last_purchase
            FROM history
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRecentPurchases($user_id, $limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT h.*, g.title, g.image_url, o.order_date
            FROM history h
            JOIN games g ON h.game_id = g.game_id
            JOIN orders o ON h.order_id = o.order_id
            WHERE h.user_id = ?
            ORDER BY h.purchased_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGamePurchaseStats($game_id) {
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_purchases,
                SUM(quantity) as total_units,
                SUM(quantity * price) as total_revenue,
                AVG(price) as average_price,
                COUNT(DISTINCT user_id) as unique_buyers
            FROM history
            WHERE game_id = ?
        ");
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?> 