<?php
// Load configuration
$config = require_once 'config.php';

// Load database connection
require_once 'core/db_connection.php';

// Load all models first
foreach (glob("model/*.php") as $filename) {
    require_once $filename;
}

// Load all controllers
foreach (glob("Controller/*.php") as $filename) {
    require_once $filename;
}

// Get URL parameters
$url = isset($_GET['url']) ? $_GET['url'] : 'home/home';
$urlArr = explode('/', $url);

// Default controller and action
$controllerName = 'Home';
$action = 'index';

// Set controller and action based on URL
if (count($urlArr) >= 1 && !empty($urlArr[0])) {
    $controllerName = ucfirst($urlArr[0]);
}

if (count($urlArr) >= 2 && !empty($urlArr[1])) {
    $action = $urlArr[1];
}

// Create controller instance and call action
$controllerClassName = $controllerName . 'Controller';

if (class_exists($controllerClassName)) {
    $controller = new $controllerClassName($conn);
    
    if (method_exists($controller, $action)) {
        call_user_func([$controller, $action]);
    } else {
        // Action not found, show 404
        require_once 'view/error/404.php';
    }
} else {
    // Controller not found, show 404
    require_once 'view/error/404.php';
} 