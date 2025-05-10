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
        $csrf_token = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;

        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            header('Location: ' . $this->config['base']);
            exit;
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = [
                    'username' => ['required', 'min:3', 'max:50'],
                    'password' => ['required', 'min:6']
                ];

                if (!$this->validate($_POST, $rules)) {
                    return $this->view('auth/login', [
                        'title' => 'Đăng nhập',
                        'css_files' => ['auth'],
                        'csrf_token' => $csrf_token
                    ]);
                }

                $username = $this->sanitize($_POST['username']);
                $password = $_POST['password'];
                
                // Check login attempts
                if ($this->isLockedOut($username)) {
                    $this->addError('general', 'Account is temporarily locked. Please try again later.');
                    return $this->view('auth/login', [
                        'title' => 'Đăng nhập',
                        'css_files' => ['auth'],
                        'csrf_token' => $csrf_token
                    ]);
                }

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
                    $this->view('auth/login', [
                        'title' => 'Đăng nhập',
                        'css_files' => ['auth'],
                        'csrf_token' => $csrf_token
                    ]);
                }
            } else {
            $this->view('auth/login', [
                'title' => 'Đăng nhập',
                'css_files' => ['auth'],
                'csrf_token' => $csrf_token
            ]);
            }
        } catch (Exception $e) {
            $this->logError('Login error', $e);
            $this->error500();
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

                if ($result) {
                    error_log("User successfully registered: " . $username);
                    $this->redirect('auth/login');
                } else {
                    error_log("Failed to save user to database. Query might have failed.");
                    return $this->view('auth/register', ['csrf_token' => $csrf_token, 'error' => 'Failed to register user.']);
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate CSRF token
                $this->validateCSRF($_POST['csrf_token'] ?? '');

                // Validate input
                $rules = [
                    'email' => ['required', 'email'],
                    'full_name' => ['required', 'min:2', 'max:100']
                ];
                
                if (!empty($_POST['new_password'])) {
                    $rules['current_password'] = ['required'];
                    $rules['new_password'] = ['required', 'min:' . $this->passwordMinLength];
                    $rules['password_confirm'] = ['required', 'same:new_password'];
                }

                if (!$this->validate($_POST, $rules)) {
                    return $this->view('auth/profile', [
                        'title' => 'Thông tin cá nhân',
                        'css_files' => ['auth']
                    ]);
                }

                // Begin transaction
                $this->conn->begin_transaction();

                try {
                    $userData = [
                        'email' => $this->sanitize($_POST['email']),
                        'full_name' => $this->sanitize($_POST['full_name'])
                    ];

                    // Handle password change
                    if (!empty($_POST['new_password'])) {
                        if (!password_verify($_POST['current_password'], $this->user['password_hash'])) {
                            $this->addError('current_password', 'Current password is incorrect');
                            throw new Exception('Invalid current password');
                }
                
                        if (!$this->validatePasswordStrength($_POST['new_password'])) {
                            throw new Exception('Password does not meet requirements');
                        }

                        $userData['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    }

                    // Update user
                    $this->userModel->updateUser($this->user['id'], $userData);
                    
                    // Commit transaction
                    $this->conn->commit();
                    
                    // Refresh user data
                    $this->loadUser();
                    
                    $this->redirect('auth/profile', ['success' => 'Profile updated successfully']);
                } catch (Exception $e) {
                    $this->conn->rollback();
                    throw $e;
                }
            }
            
            $this->view('auth/profile', [
                'title' => 'Thông tin cá nhân',
                'css_files' => ['auth']
            ]);
        } catch (Exception $e) {
            $this->logError('Profile update error', $e);
            $this->error500();
        }
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