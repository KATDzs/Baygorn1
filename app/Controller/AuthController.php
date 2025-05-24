<?php
class AuthController extends BaseController {
    private $userModel;
    private $maxLoginAttempts = 5;
    private $lockoutTime = 900; // 15 minutes
    private $passwordMinLength = 8;
    private $maxRegistrationAttempts = 3; // Max registration attempts per hour
    private $registrationLockoutTime = 3600; // 1 hour
    private $passwordRequirements = [
        'uppercase' => true,
        'lowercase' => true,
        'number' => true,
        'special' => true
    ];
    private $errors = [];

    public function __construct($conn) {
        parent::__construct($conn);
        $this->userModel = $this->loadModel('UserModel');
    }

    public function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    // Hiển thị form đăng nhập
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
            try {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = [
                    'username' => ['required', 'min:3', 'max:50'],
                    'password' => ['required', 'min:6']
                ];

                if (!$this->validate($_POST, $rules)) {
                    $csrf_token = bin2hex(random_bytes(32));
                    $_SESSION['csrf_token'] = $csrf_token;
                    return $this->view('auth/login', [
                        'title' => 'Đăng nhập',
                        'css_files' => ['auth'],
                        'csrf_token' => $csrf_token,
                        'errors' => $this->getErrors()
                    ]);
                }

                $username = $this->sanitize($_POST['username']);
                $password = $_POST['password'];
                
                // BỎ kiểm tra locked out
                // if ($this->isLockedOut($username)) {
                //     $this->addError('general', 'Account is temporarily locked. Please try again later.');
                //     $csrf_token = bin2hex(random_bytes(32));
                //     $_SESSION['csrf_token'] = $csrf_token;
                //     return $this->view('auth/login', [
                //         'title' => 'Đăng nhập',
                //         'css_files' => ['auth'],
                //         'csrf_token' => $csrf_token,
                //         'errors' => $this->getErrors()
                //     ]);
                // }

                // Attempt login
                    $user = $this->userModel->getUserByUsername($username);
                    
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Reset login attempts
                    $this->resetLoginAttempts($username);

                    // Check if password needs rehash
                    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                        $this->userModel->updatePasswordHash($user['user_id'], $password);
                    }

                    // Set session data
                    $this->initializeUserSession($user);

                    // Nếu có redirect_after_login thì chuyển về đó
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirectUrl = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header("Location: $redirectUrl");
                        exit;
                    }

                    // Redirect based on role
                    $this->redirect($user['is_admin'] ? 'admin' : 'home');
                } else {
                    // Increment failed login attempts
                    $this->incrementLoginAttempts($username);
                    $this->addError('general', 'Invalid username or password');
                    $csrf_token = bin2hex(random_bytes(32));
                    $_SESSION['csrf_token'] = $csrf_token;
                    return $this->view('auth/login', [
                        'title' => 'Đăng nhập',
                        'css_files' => ['auth'],
                        'csrf_token' => $csrf_token,
                        'errors' => $this->getErrors()
                    ]);
                }
            } catch (Exception $e) {
                $this->logError('Login error', $e);
                $this->error500();
            }
        } else {
            // GET: luôn tạo token mới khi vào trang login
            $csrf_token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrf_token;
            $this->view('auth/login', [
                'title' => 'Đăng nhập',
                'css_files' => ['auth'],
                'csrf_token' => $csrf_token
            ]);
        }
    }

    // Hiển thị form đăng ký
    public function register() {
        $this->startSession();
        $csrf_token = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Session CSRF Token: " . ($_SESSION['csrf_token'] ?? 'null'));
            error_log("Form CSRF Token: " . ($_POST['csrf_token'] ?? 'null'));

            try {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = [
                    'username' => ['required', 'min:3', 'max:20'],
                    'email' => ['required', 'email'],
                    'password' => ['required', 'min:6'],
                    'confirm_password' => ['required', 'same:password'],
                    'full_name' => ['required', 'min:2', 'max:100']
                ];

                if (!$this->validate($_POST, $rules)) {
                    error_log("Validation errors: " . print_r($_SESSION['validation_errors'], true));
                    return $this->view('auth/register', ['csrf_token' => $csrf_token]);
                }

                // Sanitize input
                $username = $this->sanitize($_POST['username']);
                $email = $this->sanitize($_POST['email']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $full_name = $this->sanitize($_POST['full_name']);

                // Save to database
                $userModel = $this->loadModel('UserModel');
                $result = $userModel->createUser([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'full_name' => $full_name
                ]);

                if ($result === 'duplicate_username') {
                    $_SESSION['register_error'] = 'Tên đăng nhập đã tồn tại.';
                    header('Location: ' . $this->config['baseURL'] . 'auth/register');
                    exit;
                } elseif ($result === 'duplicate_email') {
                    $_SESSION['register_error'] = 'Email đã được sử dụng.';
                    header('Location: ' . $this->config['baseURL'] . 'auth/register');
                    exit;
                } elseif ($result) {
                    $_SESSION['register_success'] = 'Đăng ký thành công! Bạn có thể đăng nhập.';
                    header('Location: ' . $this->config['baseURL'] . 'auth/login');
                    exit;
                } else {
                    $_SESSION['register_error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
                    header('Location: ' . $this->config['baseURL'] . 'auth/register');
                    exit;
                }
            } catch (Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                return $this->view('auth/register', ['csrf_token' => $csrf_token, 'error' => 'An error occurred during registration.']);
            }
        }

        $this->view('auth/register', ['csrf_token' => $csrf_token]);
    }

    // Đăng xuất
    public function logout() {
        try {
            // Clear all session data
            session_unset();
        session_destroy();
            
            // Clear remember-me cookie if exists
            if (isset($_COOKIE['remember_token'])) {
                $this->userModel->deleteRememberToken($_COOKIE['remember_token']);
                setcookie('remember_token', '', time() - 3600, '/');
            }
            
            $this->redirect('auth/login', ['success' => 'You have been logged out successfully']);
        } catch (Exception $e) {
            $this->logError('Logout error', $e);
            $this->error500();
        }
    }

    public function profile() {
        $this->requireLogin();

        try {
            // Get user data from model
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            
            if (!$user) {
                throw new Exception('User not found');
            }

            // Pass user data to view
            $this->view('auth/profile', [
                'title' => 'Thông tin cá nhân',
                'css_files' => ['auth'],
                'user' => $user
            ]);
        } catch (Exception $e) {
            $this->logError('Profile error', $e);
            $_SESSION['message'] = 'Có lỗi xảy ra khi tải thông tin cá nhân';
            $_SESSION['message_type'] = 'danger';
            $this->redirect('home');
        }
    }

    public function update_profile() {
        $this->requireLogin();

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Validate input
            $rules = [
                'email' => ['required', 'email'],
                'fullname' => ['required', 'min:2', 'max:100'],
                'phone' => ['optional', 'max:20'],
                'address' => ['optional', 'max:255']
            ];

            if (!$this->validate($_POST, $rules)) {
                throw new Exception('Invalid input data');
            }

            // Update user data
            $userData = [
                'email' => $this->sanitize($_POST['email']),
                'fullname' => $this->sanitize($_POST['fullname']),
                'phone' => $this->sanitize($_POST['phone'] ?? ''),
                'address' => $this->sanitize($_POST['address'] ?? '')
            ];

            $result = $this->userModel->updateUser($_SESSION['user_id'], $userData);

            if ($result) {
                $_SESSION['message'] = 'Cập nhật thông tin thành công';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to update profile');
            }

        } catch (Exception $e) {
            $this->logError('Update profile error', $e);
            $_SESSION['message'] = 'Có lỗi xảy ra khi cập nhật thông tin';
            $_SESSION['message_type'] = 'danger';
        }

        $this->redirect('auth/profile');
    }

    public function change_password() {
        $this->requireLogin();

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Validate input
            $rules = [
                'current_password' => ['required'],
                'new_password' => ['required', 'min:' . $this->passwordMinLength],
                'confirm_password' => ['required', 'same:new_password']
            ];

            if (!$this->validate($_POST, $rules)) {
                throw new Exception('Invalid input data');
            }

            // Get current user data
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            
            if (!$user) {
                throw new Exception('User not found');
            }

            // Verify current password
            if (!password_verify($_POST['current_password'], $user['password_hash'])) {
                throw new Exception('Mật khẩu hiện tại không đúng');
            }

            // Validate new password strength
            if (!$this->validatePasswordStrength($_POST['new_password'])) {
                throw new Exception('Mật khẩu mới không đủ mạnh');
            }

            // Update password
            $newPasswordHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $result = $this->userModel->updatePassword($_SESSION['user_id'], $newPasswordHash);

            if ($result) {
                $_SESSION['message'] = 'Đổi mật khẩu thành công';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to update password');
            }

        } catch (Exception $e) {
            $this->logError('Change password error', $e);
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        $this->redirect('auth/profile');
    }

    public function forgotPassword() {
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = ['email' => ['required', 'email']];
                if (!$this->validate($_POST, $rules)) {
                    return $this->view('auth/forgot-password', [
                        'title' => 'Quên mật khẩu',
                        'css_files' => ['auth']
                    ]);
                }

                $email = $this->sanitize($_POST['email']);
                $user = $this->userModel->getUserByEmail($email);

                if ($user) {
                    // Generate reset token
                    $token = bin2hex(random_bytes(32));
                    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
                    // Store reset token
                    $this->userModel->storeResetToken($user['user_id'], $token, $expiry);
                    
                    // Send reset email
                    $resetLink = SITE_URL . '/auth/reset-password?token=' . $token;
                    $this->sendPasswordResetEmail($email, $user['full_name'], $resetLink);
                }

                // Always show success message to prevent email enumeration
                $this->redirect('auth/login', [
                    'success' => 'If your email exists in our system, you will receive password reset instructions.'
                ]);
            }

            $this->view('auth/forgot-password', [
                'title' => 'Quên mật khẩu',
                'css_files' => ['auth']
            ]);
        } catch (Exception $e) {
            $this->logError('Forgot password error', $e);
            $this->error500();
        }
    }

    public function resetPassword() {
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }

        try {
            $token = $_GET['token'] ?? '';
            if (empty($token)) {
                $this->redirect('auth/login');
            }

            // Verify token
            $resetInfo = $this->userModel->getResetToken($token);
            if (!$resetInfo || strtotime($resetInfo['expiry']) < time()) {
                $this->redirect('auth/login', [
                    'error' => 'Invalid or expired password reset link.'
                ]);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = [
                    'password' => ['required', 'min:' . $this->passwordMinLength],
                    'password_confirm' => ['required', 'same:password']
                ];

                if (!$this->validate($_POST, $rules)) {
                    return $this->view('auth/reset-password', [
                        'title' => 'Đặt lại mật khẩu',
                        'css_files' => ['auth'],
                        'token' => $token
                    ]);
                }

                // Additional password validation
                if (!$this->validatePasswordStrength($_POST['password'])) {
                    return $this->view('auth/reset-password', [
                        'title' => 'Đặt lại mật khẩu',
                        'css_files' => ['auth'],
                        'token' => $token
                    ]);
                }

                // Update password
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $this->userModel->updatePassword($resetInfo['user_id'], $password_hash);
                
                // Invalidate reset token
                $this->userModel->invalidateResetToken($token);
                
                // Force logout from all devices
                $this->userModel->invalidateAllSessions($resetInfo['user_id']);

                $this->redirect('auth/login', [
                    'success' => 'Your password has been reset successfully. Please login with your new password.'
                ]);
            }

            $this->view('auth/reset-password', [
                'title' => 'Đặt lại mật khẩu',
                'css_files' => ['auth'],
                'token' => $token
            ]);
        } catch (Exception $e) {
            $this->logError('Password reset error', $e);
            $this->error500();
        }
    }

    private function validatePasswordStrength($password) {
        if (strlen($password) < $this->passwordMinLength) {
            $this->addError('password', "Password must be at least {$this->passwordMinLength} characters");
            return false;
        }

        if ($this->passwordRequirements['uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $this->addError('password', 'Password must contain at least one uppercase letter');
            return false;
        }

        if ($this->passwordRequirements['lowercase'] && !preg_match('/[a-z]/', $password)) {
            $this->addError('password', 'Password must contain at least one lowercase letter');
            return false;
        }

        if ($this->passwordRequirements['number'] && !preg_match('/[0-9]/', $password)) {
            $this->addError('password', 'Password must contain at least one number');
            return false;
        }

        if ($this->passwordRequirements['special'] && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $this->addError('password', 'Password must contain at least one special character');
            return false;
        }

        return true;
    }

    private function isLockedOut($username) {
        $attempts = $this->userModel->getLoginAttempts($username);
        if ($attempts['count'] >= $this->maxLoginAttempts) {
            $lockoutEnd = strtotime($attempts['last_attempt']) + $this->lockoutTime;
            return time() < $lockoutEnd;
        }
        return false;
    }

    private function incrementLoginAttempts($username) {
        $this->userModel->incrementLoginAttempts($username);
    }

    private function resetLoginAttempts($username) {
        $this->userModel->resetLoginAttempts($username);
    }

    private function initializeUserSession($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['last_activity'] = time();

        // Set remember-me cookie if requested
        if (isset($_POST['remember']) && $_POST['remember']) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->storeRememberToken($user['user_id'], $token);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
        }
    }

    private function sendWelcomeEmail($email, $name) {
        // Implement email sending logic here
        // You might want to use a proper email library like PHPMailer
    }

    private function isRegistrationLocked($ip) {
        $attempts = isset($_SESSION['registration_attempts'][$ip]) 
            ? $_SESSION['registration_attempts'][$ip] 
            : ['count' => 0, 'timestamp' => time()];
        
        // Reset if lockout time has passed
        if (time() - $attempts['timestamp'] > $this->registrationLockoutTime) {
            $_SESSION['registration_attempts'][$ip] = ['count' => 0, 'timestamp' => time()];
            return false;
        }
        
        return $attempts['count'] >= $this->maxRegistrationAttempts;
    }

    private function incrementRegistrationAttempts($ip) {
        if (!isset($_SESSION['registration_attempts'][$ip])) {
            $_SESSION['registration_attempts'][$ip] = ['count' => 0, 'timestamp' => time()];
        }
        $_SESSION['registration_attempts'][$ip]['count']++;
    }

    private function sendPasswordResetEmail($email, $name, $resetLink) {
        $subject = 'Password Reset Request';
        $message = "Hello {$name},\n\n";
        $message .= "You have requested to reset your password. Click the link below to proceed:\n\n";
        $message .= $resetLink . "\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you did not request this password reset, please ignore this email.\n\n";
        $message .= "Best regards,\nThe Team";

        // Send email using your preferred method
        mail($email, $subject, $message);
    }
}