<?php
class UserController {
    private $userModel;
    private $orderModel;
    private $historyModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/UserModel.php';
        require_once 'model/OrderModel.php';
        require_once 'model/HistoryModel.php';
        
        global $conn;
        $this->userModel = new UserModel($conn);
        $this->orderModel = new OrderModel($conn);
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
        $user = $this->userModel->getUserById($userId);
        
        require_once 'view/layout/header.php';
        require_once 'view/user/profile.php';
        require_once 'view/layout/footer.php';
    }

    public function history() {
        $userId = $this->checkAuth();
        
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        $history = $this->historyModel->getUserHistory($userId, $limit, $offset);
        $total = $this->historyModel->getTotalPurchases($userId);
        $totalPages = ceil($total / $limit);
        
        require_once 'view/layout/header.php';
        require_once 'view/user/history.php';
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
                case 'profile':
                    $response = $this->getProfile($userId);
                    break;
                case 'purchase_history':
                    $limit = $_GET['limit'] ?? 10;
                    $offset = $_GET['offset'] ?? 0;
                    $response = $this->getPurchaseHistory($userId, $limit, $offset);
                    break;
                case 'order_history':
                    $response = $this->getOrderHistory($userId);
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
                case 'update_profile':
                    if (!isset($_POST['email']) || !isset($_POST['full_name'])) {
                        $this->sendJsonResponse(['success' => false, 'message' => 'Missing required parameters']);
                    }
                    $response = $this->updateProfile(
                        $userId,
                        $_POST['email'],
                        $_POST['full_name']
                    );
                    break;
                case 'change_password':
                    if (!isset($_POST['current_password']) || !isset($_POST['new_password'])) {
                        $this->sendJsonResponse(['success' => false, 'message' => 'Missing required parameters']);
                    }
                    $response = $this->changePassword(
                        $userId,
                        $_POST['current_password'],
                        $_POST['new_password']
                    );
                    break;
                default:
                    $response = ['success' => false, 'message' => 'Invalid action'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid request method'];
        }

        $this->sendJsonResponse($response);
    }

    public function getProfile($user_id) {
        $user = $this->userModel->getUserById($user_id);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Get user's recent orders
        $orders = $this->orderModel->getUserOrders($user_id, 5);
        
        // Get user's purchase history
        $history = $this->historyModel->getUserHistory($user_id);
        
        // Get purchase statistics
        $stats = $this->historyModel->getTotalPurchases($user_id);

        return [
            'success' => true,
            'data' => [
                'user' => $user,
                'orders' => $orders,
                'history' => $history,
                'stats' => $stats
            ]
        ];
    }

    public function updateProfile($user_id, $email, $full_name) {
        // Validate input
        if (empty($email) || empty($full_name)) {
            return ['success' => false, 'message' => 'Email and full name are required'];
        }

        // Check if email is already taken by another user
        $existingUser = $this->userModel->getUserByEmail($email);
        if ($existingUser && $existingUser['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Email already taken'];
        }

        // Update user profile
        if ($this->userModel->updateUser($user_id, $email, $full_name)) {
            return ['success' => true, 'message' => 'Profile updated successfully'];
        }

        return ['success' => false, 'message' => 'Failed to update profile'];
    }

    public function changePassword($user_id, $current_password, $new_password) {
        // Get user data
        $user = $this->userModel->getUserById($user_id);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Verify current password
        if (!password_verify($current_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Hash new password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        if ($this->userModel->updatePassword($user_id, $new_password_hash)) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        }

        return ['success' => false, 'message' => 'Failed to change password'];
    }

    public function getPurchaseHistory($user_id, $limit = 10, $offset = 0) {
        $history = $this->historyModel->getUserHistory($user_id);
        $total = $this->historyModel->getTotalPurchases($user_id);

        return [
            'success' => true,
            'data' => [
                'history' => $history,
                'total' => $total
            ]
        ];
    }

    public function getOrderHistory($user_id) {
        $orders = $this->orderModel->getUserOrders($user_id);
        return [
            'success' => true,
            'data' => $orders
        ];
    }
} 