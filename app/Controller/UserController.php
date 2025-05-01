<?php
class UserController extends BaseController {
    private $userModel;
    private $orderModel;
    private $historyModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->userModel = $this->loadModel('UserModel');
        $this->orderModel = $this->loadModel('OrderModel');
        $this->historyModel = $this->loadModel('HistoryModel');
    }

    public function index() {
        try {
            $userId = $this->requireLogin();
            $user = $this->userModel->getUserById($userId);
            
            $this->view('user/profile', [
                'title' => 'Thông tin cá nhân',
                'user' => $user,
                'css_files' => ['user']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function history() {
        try {
            $userId = $this->requireLogin();
            
            $limit = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;
            
            $history = $this->historyModel->getUserHistory($userId, $limit, $offset);
            $total = $this->historyModel->getTotalPurchases($userId);
            $totalPages = ceil($total / $limit);
            
            $this->view('user/history', [
                'title' => 'Lịch sử mua hàng',
                'history' => $history,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'css_files' => ['user']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function api() {
        try {
            $userId = $this->requireLogin();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!isset($_GET['action'])) {
                    $this->json(['success' => false, 'message' => 'No action specified']);
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
                    $this->json(['success' => false, 'message' => 'No action specified']);
                    return;
                }

                switch ($_POST['action']) {
                    case 'update_profile':
                        if (!isset($_POST['email']) || !isset($_POST['full_name'])) {
                            $this->json(['success' => false, 'message' => 'Missing required parameters']);
                        }
                        $response = $this->updateProfile(
                            $userId,
                            $_POST['email'],
                            $_POST['full_name']
                        );
                        break;
                    case 'change_password':
                        if (!isset($_POST['current_password']) || !isset($_POST['new_password'])) {
                            $this->json(['success' => false, 'message' => 'Missing required parameters']);
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

            $this->json($response);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->json(['success' => false, 'message' => 'Internal server error']);
        }
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