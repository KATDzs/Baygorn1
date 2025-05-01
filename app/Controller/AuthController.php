<?php
class AuthController {
    private $userModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/UserModel.php';
        
        global $conn;
        $this->userModel = new UserModel($conn);
    }

    // Hiển thị form đăng nhập
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->getUserByUsername($username);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                if ($user['is_admin']) {
                    header('Location: /Baygorn1/admin');
                } else {
                    header('Location: /Baygorn1/home');
                }
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        }
        
        require_once 'view/layout/header.php';
        require_once 'view/auth/login.php';
        require_once 'view/layout/footer.php';
    }

    // Hiển thị form đăng ký
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'email' => $_POST['email'] ?? '',
                'full_name' => $_POST['full_name'] ?? ''
            ];
            
            // Validate input
            $errors = [];
            if (empty($data['username'])) {
                $errors[] = 'Username is required';
            }
            if (empty($data['password'])) {
                $errors[] = 'Password is required';
            }
            if (empty($data['email'])) {
                $errors[] = 'Email is required';
            }
            if (empty($data['full_name'])) {
                $errors[] = 'Full name is required';
            }
            
            // Check if username exists
            if ($this->userModel->getUserByUsername($data['username'])) {
                $errors[] = 'Username already exists';
            }
            
            // Check if email exists
            if ($this->userModel->getUserByEmail($data['email'])) {
                $errors[] = 'Email already exists';
            }
            
            if (empty($errors)) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if ($this->userModel->createUser($data)) {
                    header('Location: /Baygorn1/auth/login');
                    exit;
                } else {
                    $errors[] = 'Registration failed';
                }
            }
        }
        
        require_once 'view/layout/header.php';
        require_once 'view/auth/register.php';
        require_once 'view/layout/footer.php';
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        header('Location: /Baygorn1/auth/login');
        exit;
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Baygorn1/auth/login');
            exit;
        }
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $_POST['email'] ?? $user['email'],
                'full_name' => $_POST['full_name'] ?? $user['full_name']
            ];
            
            // Update password if provided
            if (!empty($_POST['new_password'])) {
                if (password_verify($_POST['current_password'], $user['password'])) {
                    $data['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                } else {
                    $error = 'Current password is incorrect';
                }
            }
            
            if (!isset($error)) {
                if ($this->userModel->updateUser($_SESSION['user_id'], $data)) {
                    $success = 'Profile updated successfully';
                    $user = $this->userModel->getUserById($_SESSION['user_id']);
                } else {
                    $error = 'Failed to update profile';
                }
            }
        }
        
        require_once 'view/layout/header.php';
        require_once 'view/auth/profile.php';
        require_once 'view/layout/footer.php';
    }
}