<?php
class AuthController extends BaseController {
    private $userModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->userModel = $this->loadModel('UserModel');
    }

    // Hiển thị form đăng nhập
    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate required fields
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                
                if (empty($username) || empty($password)) {
                    $_SESSION['login_error'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu';
                    $this->redirect('auth/login');
                } else {
                    $user = $this->userModel->getUserByUsername($username);
                    
                    // Debug information
                    error_log("Login attempt - Username: " . $username);
                    error_log("User data from DB: " . print_r($user, true));
                    
                    if ($user) {
                        error_log("Password verification - Input: " . $password);
                        error_log("Stored hash: " . $user['password_hash']);
                        $verified = password_verify($password, $user['password_hash']);
                        error_log("Password verified: " . ($verified ? 'true' : 'false'));
                    }
                    
                    if ($user && password_verify($password, $user['password_hash'])) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['is_admin'] = $user['is_admin'];
                        
                        if ($user['is_admin']) {
                            $this->redirect('admin');
                        } else {
                            $this->redirect('home');
                        }
                    } else {
                        $_SESSION['login_error'] = 'Tên đăng nhập hoặc mật khẩu không đúng';
                        $this->redirect('auth/login');
                    }
                }
            }
            
            // Lấy lỗi từ session (nếu có), sau đó xóa đi để F5 không còn lỗi
            $error = $_SESSION['login_error'] ?? null;
            unset($_SESSION['login_error']);
            $this->view('auth/login', [
                'title' => 'Đăng nhập',
                'error' => $error,
                'css_files' => ['auth']
            ]);
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->view('error/404');
        }
    }

    // Hiển thị form đăng ký
    public function register() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate and sanitize input
                $data = [
                    'username' => trim($_POST['username'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'full_name' => trim($_POST['full_name'] ?? '')
                ];
                
                // Validate input
                $errors = [];
                if (empty($data['username'])) {
                    $errors[] = 'Vui lòng nhập tên đăng nhập';
                } elseif (strlen($data['username']) < 3 || strlen($data['username']) > 20) {
                    $errors[] = 'Tên đăng nhập phải từ 3-20 ký tự';
                }
                
                if (empty($data['password'])) {
                    $errors[] = 'Vui lòng nhập mật khẩu';
                } elseif (strlen($data['password']) < 6) {
                    $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
                }
                
                if (empty($data['email'])) {
                    $errors[] = 'Vui lòng nhập email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Email không hợp lệ';
                }
                
                if (empty($data['full_name'])) {
                    $errors[] = 'Vui lòng nhập họ tên';
                }
                
                // Check if username exists
                if (empty($errors) && $this->userModel->getUserByUsername($data['username'])) {
                    $errors[] = 'Tên đăng nhập đã tồn tại';
                }
                
                // Check if email exists
                if (empty($errors) && $this->userModel->getUserByEmail($data['email'])) {
                    $errors[] = 'Email đã được sử dụng';
                }
                
                if (empty($errors)) {
                    // Hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
                    if ($this->userModel->createUser($data)) {
                        $this->redirect('auth/login');
                    } else {
                        $errors[] = 'Đăng ký không thành công, vui lòng thử lại';
                    }
                }
            }
            
            $this->view('auth/register', [
                'title' => 'Đăng ký',
                'error' => !empty($errors) ? implode('<br>', $errors) : null,
                'css_files' => ['auth']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }

    public function profile() {
        try {
            $userId = $this->requireLogin();
            $user = $this->userModel->getUserById($userId);
            
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
                    if ($this->userModel->updateUser($userId, $data)) {
                        $success = 'Profile updated successfully';
                        $user = $this->userModel->getUserById($userId);
                    } else {
                        $error = 'Failed to update profile';
                    }
                }
            }
            
            $this->view('auth/profile', [
                'title' => 'Thông tin cá nhân',
                'user' => $user,
                'error' => $error ?? null,
                'success' => $success ?? null,
                'css_files' => ['auth']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }
}