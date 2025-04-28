<?php
class OrderController {
    private $orderModel;
    private $cartModel;
    private $gameModel;
    private $historyModel;

    public function __construct($conn) {
        $this->orderModel = new OrderModel($conn);
        $this->cartModel = new CartModel($conn);
        $this->gameModel = new GameModel($conn);
        $this->historyModel = new HistoryModel($conn);
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

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include('../model/db_connection.php');
    
    $orderController = new OrderController($conn);
    $response = [];

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = ['success' => false, 'message' => 'Please login first'];
    } else {
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'details':
                        if (isset($_GET['id'])) {
                            $response = $orderController->getOrderDetails($user_id, $_GET['id']);
                        }
                        break;
                    case 'history':
                        $response = $orderController->getUserOrders($user_id);
                        break;
                }
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'create':
                        $response = $orderController->createOrder($user_id);
                        break;
                    case 'update_status':
                        if (isset($_POST['order_id']) && isset($_POST['status'])) {
                            $response = $orderController->updateOrderStatus($user_id, $_POST['order_id'], $_POST['status']);
                        }
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