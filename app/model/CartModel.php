<?php
class CartModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartItems($userId) {
        $query = "SELECT ci.*, g.title, g.price, g.image_url 
                 FROM cart_items ci
                 JOIN carts c ON ci.cart_id = c.cart_id
                 JOIN games g ON ci.game_id = g.game_id
                 WHERE c.user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function getCartTotal($userId) {
        $query = "SELECT SUM(g.price) as total 
                 FROM cart_items ci
                 JOIN carts c ON ci.cart_id = c.cart_id
                 JOIN games g ON ci.game_id = g.game_id
                 WHERE c.user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['total'] ?? 0;
    }

    public function addToCart($userId, $gameId) {
        $cartId = $this->getCartIdByUserId($userId);
        if (!$cartId) {
            $cartId = $this->createCartForUser($userId);
        }
        $query = "SELECT * FROM cart_items WHERE cart_id = ? AND game_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $cartId, $gameId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existingItem = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if ($existingItem) {
            return false;
        } else {
            $query = "INSERT INTO cart_items (cart_id, game_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ii", $cartId, $gameId);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $success;
        }
    }

    private function getCartIdByUserId($userId) {
        $query = "SELECT cart_id FROM carts WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['cart_id'] ?? null;
    }

    private function createCartForUser($userId) {
        $now = date('Y-m-d H:i:s');
        $query = "INSERT INTO carts (user_id, created_at, updated_at) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $userId, $now, $now);
        mysqli_stmt_execute($stmt);
        $cartId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);
        return $cartId;
    }

    public function removeFromCart($cartItemId) {
        $query = "DELETE FROM cart_items WHERE cart_item_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $cartItemId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function clearCart($userId) {
        $query = "DELETE FROM cart_items WHERE cart_id IN (SELECT cart_id FROM carts WHERE user_id = ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}
?>