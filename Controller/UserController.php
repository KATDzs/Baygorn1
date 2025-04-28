<?php
class UserController {
    private $userModel;
    private $orderModel;
    private $historyModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
        $this->orderModel = new OrderModel($conn);
        $this->historyModel = new HistoryModel($conn);
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

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include('db_connection.php');
    
    $userController = new UserController($conn);
    $response = [];

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = ['success' => false, 'message' => 'Please login first'];
    } else {
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'profile':
                        $response = $userController->getProfile($user_id);
                        break;
                    case 'purchase_history':
                        $limit = $_GET['limit'] ?? 10;
                        $offset = $_GET['offset'] ?? 0;
                        $response = $userController->getPurchaseHistory($user_id, $limit, $offset);
                        break;
                    case 'order_history':
                        $response = $userController->getOrderHistory($user_id);
                        break;
                }
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'update_profile':
                        $response = $userController->updateProfile(
                            $user_id,
                            $_POST['email'] ?? '',
                            $_POST['full_name'] ?? ''
                        );
                        break;
                    case 'change_password':
                        $response = $userController->changePassword(
                            $user_id,
                            $_POST['current_password'] ?? '',
                            $_POST['new_password'] ?? ''
                        );
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