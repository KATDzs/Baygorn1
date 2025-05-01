<?php
class OrderModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy tất cả đơn hàng với phân trang
    public function getAllOrders($limit = 10, $offset = 0) {
        $query = "SELECT o.*, u.username 
                 FROM orders o
                 JOIN users u ON o.user_id = u.user_id
                 ORDER BY o.created_at DESC 
                 LIMIT ?, ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $orders;
    }

    // Lấy đơn hàng theo ID
    public function getOrderById($id) {
        // Get order details
        $query = "SELECT o.*, u.username 
                 FROM orders o
                 JOIN users u ON o.user_id = u.user_id
                 WHERE o.order_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($order) {
            // Get order items
            $query = "SELECT oi.*, g.title, g.price 
                     FROM order_items oi
                     JOIN games g ON oi.game_id = g.game_id
                     WHERE oi.order_id = ?";
                     
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $items = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
            mysqli_stmt_close($stmt);
            
            $order['items'] = $items;
        }

        return $order;
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetails($orderId) {
        $sql = "SELECT od.*, g.title, g.image_url 
                FROM order_details od 
                JOIN games g ON od.game_id = g.game_id 
                WHERE od.order_id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $details = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $details;
    }

    // Lấy đơn hàng của user
    public function getUserOrders($userId, $limit = 10, $offset = 0) {
        $query = "SELECT o.*, COUNT(oi.order_item_id) as item_count 
                 FROM orders o
                 LEFT JOIN order_items oi ON o.order_id = oi.order_id
                 WHERE o.user_id = ?
                 GROUP BY o.order_id
                 ORDER BY o.created_at DESC
                 LIMIT ?, ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $userId, $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $orders;
    }

    // Tạo đơn hàng mới
    public function createOrder($userId, $items, $total, $paymentMethod) {
        mysqli_begin_transaction($this->conn);
        
        try {
            // Create order
            $query = "INSERT INTO orders (user_id, total_amount, payment_method, status, created_at) 
                     VALUES (?, ?, ?, 'pending', NOW())";
                     
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ids", $userId, $total, $paymentMethod);
            $success = mysqli_stmt_execute($stmt);
            
            if (!$success) {
                throw new Exception("Failed to create order");
            }
            
            $orderId = mysqli_insert_id($this->conn);
            mysqli_stmt_close($stmt);

            // Add order items
            foreach ($items as $item) {
                $query = "INSERT INTO order_items (order_id, game_id, quantity, price) 
                         VALUES (?, ?, ?, ?)";
                         
                $stmt = mysqli_prepare($this->conn, $query);
                mysqli_stmt_bind_param($stmt, "iiid", 
                    $orderId, 
                    $item['game_id'], 
                    $item['quantity'], 
                    $item['price']
                );
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                if (!$success) {
                    throw new Exception("Failed to add order item");
                }

                // Update game stock
                $query = "UPDATE games SET stock = stock - ? WHERE game_id = ?";
                $stmt = mysqli_prepare($this->conn, $query);
                mysqli_stmt_bind_param($stmt, "ii", $item['quantity'], $item['game_id']);
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                if (!$success) {
                    throw new Exception("Failed to update game stock");
                }
            }

            mysqli_commit($this->conn);
            return $orderId;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($orderId, $status) {
        $query = "UPDATE orders SET status = ?, modified_at = NOW() WHERE order_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $orderId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Lấy tổng số đơn hàng
    public function getTotalOrders() {
        $query = "SELECT COUNT(*) as total FROM orders";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    // Lấy tổng doanh thu
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_amount) FROM orders WHERE status = 'completed'";
        return mysqli_query($this->conn, $sql)->fetch_column() ?? 0;
    }

    // Lấy đơn hàng gần đây
    public function getRecentOrders($limit = 5) {
        $sql = "SELECT o.*, u.username, COUNT(od.order_detail_id) as total_items 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                LEFT JOIN order_details od ON o.order_id = od.order_id 
                GROUP BY o.order_id 
                ORDER BY o.order_date DESC 
                LIMIT ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $orders;
    }

    public function cancelOrder($orderId) {
        mysqli_begin_transaction($this->conn);
        
        try {
            // Get order items
            $query = "SELECT game_id, quantity FROM order_items WHERE order_id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $orderId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $items = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
            mysqli_stmt_close($stmt);

            // Return items to stock
            foreach ($items as $item) {
                $query = "UPDATE games SET stock = stock + ? WHERE game_id = ?";
                $stmt = mysqli_prepare($this->conn, $query);
                mysqli_stmt_bind_param($stmt, "ii", $item['quantity'], $item['game_id']);
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                if (!$success) {
                    throw new Exception("Failed to return items to stock");
                }
            }

            // Update order status
            $query = "UPDATE orders SET status = 'cancelled', modified_at = NOW() WHERE order_id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $orderId);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            if (!$success) {
                throw new Exception("Failed to update order status");
            }

            mysqli_commit($this->conn);
            return true;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }
}
?> 