<?php
class BaseController {
    protected $conn;
    protected $config;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->config = require BASE_PATH . '/config.php';
    }
    
    protected function loadModel($modelName) {
        $modelFile = BASE_PATH . '/' . $this->config['paths']['models'] . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName($this->conn);
        }
        return null;
    }

    protected function generateCSRFToken() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            error_log("Session is not active. Starting session.");
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("Generated CSRF Token: " . $_SESSION['csrf_token']);
        }
        return $_SESSION['csrf_token'];
    }

    protected function startSession() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function validateCSRF($token) {
        $this->startSession();

        if (empty($token)) {
            error_log("CSRF Token is empty or not provided.");
            throw new Exception('CSRF token is missing');
        }

        if (!isset($_SESSION['csrf_token'])) {
            error_log("Session CSRF Token is not set. Session might not be initialized properly.");
            throw new Exception('CSRF token is not set in session');
        }

        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            error_log("CSRF Token mismatch detected. Session Token: " . $_SESSION['csrf_token'] . ", Provided Token: " . $token);
            throw new Exception('Invalid CSRF token');
        }

        // Regenerate CSRF token after successful validation to prevent reuse
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        error_log("CSRF Token successfully validated and regenerated: " . $_SESSION['csrf_token']);
    }

    
    
    protected function view($viewName, $data = []) {
        // Add config to all views
        $data['config'] = $this->config;
        
        // Add helper functions
        $data['asset'] = function($path) {
            return $this->config['baseURL'] . 'asset/' . $path;
        };
        
        $data['url'] = function($path) {
            return $this->config['baseURL'] . $path;
        };
        
        // Extract data to make it available in view
        extract($data);
        
        // Load header
        require_once BASE_PATH . '/' . $this->config['paths']['views'] . 'layout/header.php';
        
        // Load main view
        require_once BASE_PATH . '/' . $this->config['paths']['views'] . $viewName . '.php';
        
        // Load footer
        require_once BASE_PATH . '/' . $this->config['paths']['views'] . 'layout/footer.php';
    }

    protected function viewWithoutLayout($viewName, $data = []) {
        // Add config to view
        $data['config'] = $this->config;
        
        // Add helper functions
        $data['asset'] = function($path) {
            return $this->config['baseURL'] . 'asset/' . $path;
        };
        
        $data['url'] = function($path) {
            return $this->config['baseURL'] . $path;
        };
        
        // Extract data
        extract($data);
        
        // Load view
        require_once BASE_PATH . '/' . $this->config['paths']['views'] . $viewName . '.php';
    }
    protected function logError($message, $exception = null) {
        // Ghi log chi tiết hơn với thông tin ngoại lệ nếu có
        $logMessage = date('[Y-m-d H:i:s] ') . $message;
        if ($exception) {
            $logMessage .= "\nException: " . $exception->getMessage();
            $logMessage .= "\nFile: " . $exception->getFile();
            $logMessage .= "\nLine: " . $exception->getLine();
            $logMessage .= "\nStack trace:\n" . $exception->getTraceAsString();
        }
        error_log($logMessage . "\n", 3, __DIR__ . '/../../logs/error.log');
    }
    protected function error500() {
        http_response_code(500);
        $this->view('error/500'); // loi mat ket noi 
        exit;
    }
    
    
    protected function redirect($url) {
        header('Location: ' . $this->config['baseURL'] . $url);
        exit();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('error/403');
        }
    }
    
    protected function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field][] = 'This field is required';
                } elseif (strpos($rule, 'min:') === 0) {
                    $min = (int) substr($rule, 4);
                    if (strlen($data[$field] ?? '') < $min) {
                        $errors[$field][] = "Minimum length is $min characters";
                    }
                } elseif (strpos($rule, 'max:') === 0) {
                    $max = (int) substr($rule, 4);
                    if (strlen($data[$field] ?? '') > $max) {
                        $errors[$field][] = "Maximum length is $max characters";
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            return false;
        }

        return true;
    }

    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}