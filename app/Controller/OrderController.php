<?php
class OrderController {
    private $orderModel;
    private $cartModel;
    private $gameModel;
    private $historyModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/OrderModel.php';
        require_once 'model/CartModel.php';
        require_once 'model/GameModel.php';
        require_once 'model/HistoryModel.php';
        
        global $conn;
        $this->orderModel = new OrderModel($conn);
        $this->cartModel = new CartModel($conn);
        $this->gameModel = new GameModel($conn);
        $this->historyModel = new HistoryModel($conn);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: /Baygorn1/auth/login');
                exit;
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Please login first']);
            }
        }
        return $_SESSION['user_id'];
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function index() {
        $userId = $this->checkAuth();
        
        $orders = $this->orderModel->getUserOrders($userId);
        
        require_once 'view/layout/header.php';
        require_once 'view/order/index.php';
        require_once 'view/layout/footer.php';
    }

    public function detail($orderId) {
        $userId = $this->checkAuth();
        
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order || $order['user_id'] != $userId) {
            header('Location: /Baygorn1/order');
            exit;
        }
        
        $details = $this->orderModel->getOrderDetails($orderId);
        
        require_once 'view/layout/header.php';
        require_once 'view/order/detail.php';
        require_once 'view/layout/footer.php';
    }

    public function api() {
        $userId = $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!isset($_GET['action'])) {
                $this->sendJsonResponse(['success' => false, 'message' => 'No action specified']);
                return;
            }

            switch ($_GET['action']) {
                case 'details':
                    if (!isset($_GET['id'])) {
                        $this->sendJsonResponse(['success' => false, 'message' => 'No order ID provided']);
                    }
                    $response = $this->getOrderDetails($userId, $_GET['id']);
                    break;
                case 'history':
                    $response = $this->getUserOrders($userId);
                    break;
                default:
                    $response = ['success' => false, 'message' => 'Invalid action'];
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['action'])) {
                $this->sendJsonResponse(['success' => false, 'message' => 'No action specified']);
                return;
            }

            switch ($_POST['action']) {
                case 'create':
                    $response = $this->createOrder($userId);
                    break;
                case 'update_status':
                    if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
                        $this->sendJsonResponse(['success' => false, 'message' => 'Missing required parameters']);
                    }
                    $response = $this->updateOrderStatus($userId, $_POST['order_id'], $_POST['status']);
                    break;
                default:
                    $response = ['success' => false, 'message' => 'Invalid action'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid request method'];
        }

        $this->sendJsonResponse($response);
    }

    public function createOrder($user_id) {
        // Get cart
        $cart = $this->cartModel->getCartByUserId($user_id);
        if (!$cart || empty($cart)) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        // Calculate total amount
        $total_amount = $this->cartModel->getCartTotal($cart[0]['cart_id']);

        // Create order
        $order_id = $this->orderModel->createOrder($user_id, $total_amount);
        if (!$order_id) {
            return ['success' => false, 'message' => 'Failed to create order'];
        }

        // Add order details and update stock
        foreach ($cart as $item) {
            // Add order detail
            $this->orderModel->addOrderDetail(
                $order_id,
                $item['game_id'],
                $item['price'],
                $item['quantity']
            );

            // Update stock
            $this->gameModel->updateStock($item['game_id'], -$item['quantity']);

            // Add to history
            $this->historyModel->addToHistory(
                $user_id,
                $item['game_id'],
                $order_id,
                $item['quantity'],
                $item['price']
            );
        }

        // Clear cart
        $this->cartModel->clearCart($cart[0]['cart_id']);

        return [
            'success' => true,
            'message' => 'Order created successfully',
            'data' => ['order_id' => $order_id]
        ];
    }

    public function getOrderDetails($user_id, $order_id) {
        // Get order
        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // Check if order belongs to user
        if ($order['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        // Get order details
        $details = $this->orderModel->getOrderDetails($order_id);

        return [
            'success' => true,
            'data' => [
                'order' => $order,
                'details' => $details
            ]
        ];
    }

    public function getUserOrders($user_id) {
        $orders = $this->orderModel->getUserOrders($user_id);
        return [
            'success' => true,
            'data' => $orders
        ];
    }

    public function updateOrderStatus($user_id, $order_id, $status) {
        // Get order
        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // Check if user is admin or order owner
        if ($order['user_id'] != $user_id && !isset($_SESSION['is_admin'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        // Update status
        if ($this->orderModel->updateOrderStatus($order_id, $status)) {
            return ['success' => true, 'message' => 'Order status updated successfully'];
        }

        return ['success' => false, 'message' => 'Failed to update order status'];
    }
} 