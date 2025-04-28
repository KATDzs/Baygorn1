<?php
class OrderModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createOrder($user_id, $total_amount) {
        $order_date = date('Y-m-d H:i:s');
        $status = 'pending';
        
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, order_date, status, total_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $user_id, $order_date, $status, $total_amount);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function addOrderDetail($order_id, $game_id, $unit_price, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO order_details (order_id, game_id, unit_price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $order_id, $game_id, $unit_price, $quantity);
        return $stmt->execute();
    }

    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.username, u.email, u.full_name
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE o.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrderDetails($order_id) {
        $stmt = $this->conn->prepare("
            SELECT od.*, g.title, g.image_url
            FROM order_details od
            JOIN games g ON od.game_id = g.game_id
            WHERE od.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserOrders($user_id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, COUNT(od.order_detail_id) as item_count
            FROM orders o
            LEFT JOIN order_details od ON o.order_id = od.order_id
            WHERE o.user_id = ?
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus($order_id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }

    public function getAllOrders($limit = 0, $offset = 0) {
        $query = "
            SELECT o.*, u.username, u.email, u.full_name, COUNT(od.order_detail_id) as item_count
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            LEFT JOIN order_details od ON o.order_id = od.order_id
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
        ";
        
        if ($limit > 0) {
            $query .= " LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
        } else {
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrdersByStatus($status) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.username, u.email, u.full_name, COUNT(od.order_detail_id) as item_count
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            LEFT JOIN order_details od ON o.order_id = od.order_id
            WHERE o.status = ?
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
        ");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalOrders() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM orders");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?> 