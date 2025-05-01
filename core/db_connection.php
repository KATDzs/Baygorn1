<?php
// Define the root path if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
}

// Load configuration
$config_file = BASE_PATH . '/config.php';

// Check if config file exists
if (!file_exists($config_file)) {
    die("Configuration file not found at: " . $config_file);
}

// Load configuration
$config = require $config_file;

// Debug
if (!is_array($config)) {
    die("Config file did not return an array");
}

if (!isset($config['db'])) {
    die("Database configuration not found in config file");
}

// Verify config structure
if (!isset($config['db']['host'])) {
    die("Database host not found in config");
} 
if (!isset($config['db']['username'])) {
    die("Database username not found in config");
}
if (!isset($config['db']['password'])) {
    die("Database password not found in config");
}
if (!isset($config['db']['name'])) {
    die("Database name not found in config");
}

// Create connection
try {
    $conn = mysqli_connect(
        $config['db']['host'],
        $config['db']['username'],
        $config['db']['password'],
        $config['db']['name']
    );

    // Check connection
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    // Set charset to utf8mb4
    mysqli_set_charset($conn, "utf8mb4");

    return $conn;
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
} 