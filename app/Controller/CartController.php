<?php
class CartController extends BaseController {
    private $cartModel;
    private $gameModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->cartModel = $this->loadModel('CartModel');
        $this->gameModel = $this->loadModel('GameModel');
    }

    public function index() {
        try {
            error_log("CartController index method invoked.");
            error_log("Accessing CartController index method");
            if (!isset($_SESSION['user_id'])) {
                error_log("Session user_id is not set");
            } else {
                error_log("Session user_id: " . $_SESSION['user_id']);
            }

            $userId = $this->requireLogin();
            $cartItems = $this->cartModel->getCartItems($userId);
            error_log("Cart items fetched: " . print_r($cartItems, true));
            $total = 0;
            
            if (empty($cartItems)) {
                error_log("Cart is empty for user ID: " . $userId);
            } else {
                error_log("Cart contains " . count($cartItems) . " items for user ID: " . $userId);
            }

            foreach ($cartItems as &$item) {
                $game = $this->gameModel->getGameById($item['game_id']);
                $item['game'] = $game;
                $total += $game['price'] * $item['quantity'];
            }
            
            error_log("Rendering cart view with " . count($cartItems) . " items.");
            
            $this->view('cart/cart', [
                'title' => 'Giỏ hàng',
                'cartItems' => $cartItems,
                'total' => $total,
                'css_files' => ['cart']
            ]);
        } catch (Exception $e) {
            error_log("Error in CartController index method: " . $e->getMessage());
            $this->view('error/404');
        }
    }

    public function add() {
        try {
            $userId = $this->requireLogin();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $gameId = $_POST['game_id'] ?? 0;
                $quantity = $_POST['quantity'] ?? 1;

                if ($this->cartModel->addToCart($userId, $gameId, $quantity)) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully']);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
                    exit;
                }
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'An error occurred']);
            error_log($e->getMessage());
            exit;
        }
    }

    public function update() {
        try {
            $userId = $this->requireLogin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $cartItemId = $_POST['cart_item_id'] ?? 0;
                $quantity = $_POST['quantity'] ?? 1;
                
                if ($this->cartModel->updateCartItem($cartItemId, $quantity)) {
                    $this->redirect('cart');
                } else {
                    $error = 'Failed to update cart item';
                }
            }
            
            $this->view('cart/update', [
                'title' => 'Cập nhật giỏ hàng',
                'error' => $error ?? null,
                'css_files' => ['cart']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function remove() {
        try {
            $userId = $this->requireLogin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $cartItemId = $_POST['cart_item_id'] ?? 0;
                
                if ($this->cartModel->removeFromCart($cartItemId)) {
                    $this->redirect('cart');
                } else {
                    $error = 'Failed to remove item from cart';
                }
            }
            
            $this->view('cart/remove', [
                'title' => 'Xóa khỏi giỏ hàng',
                'error' => $error ?? null,
                'css_files' => ['cart']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
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
                    error_log("Cart is empty for user ID: " . $userId);
                    throw new Exception('Cart is empty');
                } else {
                    error_log("Cart contains " . count($cartItems) . " items for user ID: " . $userId);
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
            
            if (empty($cartItems)) {
                error_log("Cart is empty for user ID: " . $userId);
            } else {
                error_log("Cart contains " . count($cartItems) . " items for user ID: " . $userId);
            }

            require_once 'view/layout/header.php';
            require_once 'view/giaodich/process_transaction.php';
            require_once 'view/layout/footer.php';
        }
    }
}