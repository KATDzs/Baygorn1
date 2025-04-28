<?php
class HistoryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
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

    public function getUserHistory($user_id, $limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("
            SELECT h.*, g.title, g.image_url, o.order_date
            FROM history h
            JOIN games g ON h.game_id = g.game_id
            JOIN orders o ON h.order_id = o.order_id
            WHERE h.user_id = ?
            ORDER BY h.purchased_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $user_id, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGameHistory($game_id) {
        $stmt = $this->conn->prepare("
            SELECT h.*, u.username, u.email, o.order_date
            FROM history h
            JOIN users u ON h.user_id = u.user_id
            JOIN orders o ON h.order_id = o.order_id
            WHERE h.game_id = ?
            ORDER BY h.purchased_at DESC
        ");
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
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