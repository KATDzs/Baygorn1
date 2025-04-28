<?php
class CartController {
    private $cartModel;
    private $gameModel;

    public function __construct($conn) {
        $this->cartModel = new CartModel($conn);
        $this->gameModel = new GameModel($conn);
    }

    public function getCart($user_id) {
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart) {
            // Create new cart if not exists
            $this->cartModel->createCart($user_id);
            $cart = $this->cartModel->getCartByUserId($user_id);
        }

        $total = $this->cartModel->getCartTotal($cart[0]['cart_id']);
        $itemCount = $this->cartModel->getCartItemCount($cart[0]['cart_id']);

        return [
            'success' => true,
            'data' => [
                'cart' => $cart,
                'total' => $total,
                'itemCount' => $itemCount
            ]
        ];
    }

    public function addToCart($user_id, $game_id, $quantity = 1) {
        // Get cart
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart) {
            // Create new cart if not exists
            $this->cartModel->createCart($user_id);
            $cart = $this->cartModel->getCartByUserId($user_id);
        }

        // Check if game exists and has stock
        $game = $this->gameModel->getGameById($game_id);
        if (!$game) {
            return ['success' => false, 'message' => 'Game not found'];
        }

        if ($game['stock'] < $quantity) {
            return ['success' => false, 'message' => 'Not enough stock'];
        }

        // Add to cart
        if ($this->cartModel->addToCart($cart[0]['cart_id'], $game_id, $quantity)) {
            return ['success' => true, 'message' => 'Added to cart successfully'];
        }

        return ['success' => false, 'message' => 'Failed to add to cart'];
    }

    public function updateCartItem($user_id, $cart_item_id, $quantity) {
        // Get cart
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found'];
        }

        // Update cart item
        if ($this->cartModel->updateCartItemQuantity($cart_item_id, $quantity)) {
            return ['success' => true, 'message' => 'Cart updated successfully'];
        }

        return ['success' => false, 'message' => 'Failed to update cart'];
    }

    public function removeFromCart($user_id, $cart_item_id) {
        // Get cart
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found'];
        }

        // Remove from cart
        if ($this->cartModel->removeFromCart($cart_item_id)) {
            return ['success' => true, 'message' => 'Removed from cart successfully'];
        }

        return ['success' => false, 'message' => 'Failed to remove from cart'];
    }

    public function clearCart($user_id) {
        // Get cart
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found'];
        }

        // Clear cart
        if ($this->cartModel->clearCart($cart[0]['cart_id'])) {
            return ['success' => true, 'message' => 'Cart cleared successfully'];
        }

        return ['success' => false, 'message' => 'Failed to clear cart'];
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include('../model/db_connection.php');
    
    $cartController = new CartController($conn);
    $response = [];

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = ['success' => false, 'message' => 'Please login first'];
    } else {
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'view':
                        $response = $cartController->getCart($user_id);
                        break;
                }
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'add':
                        if (isset($_POST['game_id'])) {
                            $quantity = $_POST['quantity'] ?? 1;
                            $response = $cartController->addToCart($user_id, $_POST['game_id'], $quantity);
                        }
                        break;
                    case 'update':
                        if (isset($_POST['cart_item_id']) && isset($_POST['quantity'])) {
                            $response = $cartController->updateCartItem($user_id, $_POST['cart_item_id'], $_POST['quantity']);
                        }
                        break;
                    case 'remove':
                        if (isset($_POST['cart_item_id'])) {
                            $response = $cartController->removeFromCart($user_id, $_POST['cart_item_id']);
                        }
                        break;
                    case 'clear':
                        $response = $cartController->clearCart($user_id);
                        break;
                }
            }
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?> 