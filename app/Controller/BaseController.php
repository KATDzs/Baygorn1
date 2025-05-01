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
} 