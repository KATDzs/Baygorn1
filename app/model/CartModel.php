<?php
class CartModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartItems($userId) {
        $query = "SELECT c.*, g.title, g.price, g.image_url, (g.price * c.quantity) as subtotal 
                 FROM cart_items c
                 JOIN games g ON c.game_id = g.game_id
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
        $query = "SELECT SUM(g.price * c.quantity) as total 
                 FROM cart_items c
                 JOIN games g ON c.game_id = g.game_id
                 WHERE c.user_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['total'] ?? 0;
    }

    public function addToCart($userId, $gameId, $quantity = 1) {
        // Check if game exists in cart
        $query = "SELECT * FROM cart_items WHERE user_id = ? AND game_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $gameId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existingItem = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($existingItem) {
            // Update quantity
            $query = "UPDATE cart_items 
                     SET quantity = quantity + ?
                     WHERE user_id = ? AND game_id = ?";
                     
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "iii", $quantity, $userId, $gameId);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $success;
        } else {
            // Insert new item
            $query = "INSERT INTO cart_items (user_id, game_id, quantity) 
                     VALUES (?, ?, ?)";
                     
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "iii", $userId, $gameId, $quantity);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $success;
        }
    }

    public function updateQuantity($userId, $gameId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $gameId);
        }

        $query = "UPDATE cart_items 
                 SET quantity = ? 
                 WHERE user_id = ? AND game_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $userId, $gameId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function removeFromCart($userId, $gameId) {
        $query = "DELETE FROM cart_items 
                 WHERE user_id = ? AND game_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $gameId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function clearCart($userId) {
        $query = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}
?> 