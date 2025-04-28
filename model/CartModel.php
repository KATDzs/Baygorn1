<?php
class CartModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartByUserId($user_id) {
        $stmt = $this->conn->prepare("
            SELECT c.*, ci.cart_item_id, ci.game_id, ci.quantity, g.title, g.price, g.image_url
            FROM carts c
            LEFT JOIN cart_items ci ON c.cart_id = ci.cart_id
            LEFT JOIN games g ON ci.game_id = g.game_id
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createCart($user_id) {
        $created_at = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("INSERT INTO carts (user_id, created_at) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $created_at);
        return $stmt->execute();
    }

    public function addToCart($cart_id, $game_id, $quantity) {
        // Check if item already exists in cart
        $stmt = $this->conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND game_id = ?");
        $stmt->bind_param("ii", $cart_id, $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity if item exists
            $item = $result->fetch_assoc();
            $new_quantity = $item['quantity'] + $quantity;
            $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
            $stmt->bind_param("ii", $new_quantity, $item['cart_item_id']);
        } else {
            // Add new item if it doesn't exist
            $stmt = $this->conn->prepare("INSERT INTO cart_items (cart_id, game_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $cart_id, $game_id, $quantity);
        }
        
        return $stmt->execute();
    }

    public function updateCartItemQuantity($cart_item_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cart_item_id);
        }
        
        $stmt = $this->conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        $stmt->bind_param("ii", $quantity, $cart_item_id);
        return $stmt->execute();
    }

    public function removeFromCart($cart_item_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
        $stmt->bind_param("i", $cart_item_id);
        return $stmt->execute();
    }

    public function clearCart($cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param("i", $cart_id);
        return $stmt->execute();
    }

    public function getCartTotal($cart_id) {
        $stmt = $this->conn->prepare("
            SELECT SUM(ci.quantity * g.price) as total
            FROM cart_items ci
            JOIN games g ON ci.game_id = g.game_id
            WHERE ci.cart_id = ?
        ");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function getCartItemCount($cart_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    }
}
?> 