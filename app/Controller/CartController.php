<?php
class CartController {
    private $cartModel;
    private $gameModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/CartModel.php';
        require_once 'model/GameModel.php';
        
        global $conn;
        $this->cartModel = new CartModel($conn);
        $this->gameModel = new GameModel($conn);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: /Baygorn1/auth/login');
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Please login first']);
                exit;
            }
        }
    }

    public function index() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        
        $cartItems = $this->cartModel->getCartItems($userId);
        $total = $this->cartModel->getCartTotal($userId);
        
        require_once 'view/layout/header.php';
        require_once 'view/giaodich/giaodich.php';
        require_once 'view/layout/footer.php';
    }

    public function add() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameId = $_POST['game_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;
            
            if ($gameId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid game ID']);
                return;
            }
            
            // Kiểm tra số lượng tồn kho
            $game = $this->gameModel->getGameById($gameId);
            if (!$game || $game['stock'] < $quantity) {
                echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
                return;
            }
            
            $result = $this->cartModel->addToCart($userId, $gameId, $quantity);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Added to cart successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function update() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameId = $_POST['game_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;
            
            if ($gameId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid game ID']);
                return;
            }
            
            // Kiểm tra số lượng tồn kho
            $game = $this->gameModel->getGameById($gameId);
            if (!$game || $game['stock'] < $quantity) {
                echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
                return;
            }
            
            $result = $this->cartModel->updateQuantity($userId, $gameId, $quantity);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function clear() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->cartModel->clearCart($userId);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cart cleared successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to clear cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function checkout() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'model/OrderModel.php';
            global $conn;
            $orderModel = new OrderModel($conn);
            
            try {
                $cartItems = $this->cartModel->getCartItems($userId);
                if (empty($cartItems)) {
                    throw new Exception('Cart is empty');
                }
                
                // Convert cart items to order items
                $items = [];
                foreach ($cartItems as $item) {
                    $items[] = [
                        'game_id' => $item['game_id'],
                        'quantity' => $item['quantity']
                    ];
                }
                
                // Create order
                $orderId = $orderModel->createOrder($userId, $items);
                if ($orderId) {
                    // Clear cart after successful order
                    $this->cartModel->clearCart($userId);
                    
                    header('Location: /Baygorn1/giaodich/payment_confirmation.php?order_id=' . $orderId);
                    exit;
                } else {
                    throw new Exception('Failed to create order');
                }
            } catch (Exception $e) {
                require_once 'view/layout/header.php';
                require_once 'view/giaodich/process_transaction.php';
                require_once 'view/layout/footer.php';
            }
        } else {
            $cartItems = $this->cartModel->getCartItems($userId);
            $total = $this->cartModel->getCartTotal($userId);
            
            require_once 'view/layout/header.php';
            require_once 'view/giaodich/process_transaction.php';
            require_once 'view/layout/footer.php';
        }
    }
} 