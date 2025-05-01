<?php
// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base path
define('BASE_PATH', __DIR__);

// Load configuration
$config = require_once 'config.php';

// Load database connection
require_once 'core/db_connection.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Autoload function
function autoload($className) {
    // Convert namespace separators to directory separators
    $className = str_replace('\\', '/', $className);
    
    // Check for Controller
    if (strpos($className, 'Controller') !== false) {
        $file = BASE_PATH . '/app/Controller/' . $className . '.php';
    }
    // Check for Model
    else if (strpos($className, 'Model') !== false) {
        $file = BASE_PATH . '/app/model/' . $className . '.php';
    }
    
    // If file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
}

// Register autoload function
spl_autoload_register('autoload');

// Test database connection
try {
    $testQuery = mysqli_query($conn, "SELECT 1");
    if (!$testQuery) {
        throw new Exception("Database connection failed: " . mysqli_error($conn));
    }
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get URL parameters
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$urlArr = explode('/', $url);

// Default controller and action
$controllerName = ucfirst($urlArr[0]);
$action = isset($urlArr[1]) ? $urlArr[1] : 'index';

// Clean parameters
$params = array_slice($urlArr, 2);

// Add Controller suffix if not present
if (strpos($controllerName, 'Controller') === false) {
    $controllerClassName = $controllerName . 'Controller';
} else {
    $controllerClassName = $controllerName;
}

// Create controller instance and call action
$controllerFile = BASE_PATH . '/' . $config['paths']['controllers'] . $controllerClassName . '.php';

try {
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerClassName($conn);
        
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            throw new Exception("Action not found: {$action}");
        }
    } else {
        throw new Exception("Controller not found: {$controllerClassName}");
    }
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    
    // Show error page
    require_once BASE_PATH . '/' . $config['paths']['views'] . 'error/404.php';
} 