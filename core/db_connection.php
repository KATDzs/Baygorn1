<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require_once __DIR__ . '/../config.php';
        
        $host = $config['db']['host'];
        $dbname = $config['db']['name'];
        $username = $config['db']['username'];
        $password = $config['db']['password'];

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create a global database connection
$db = Database::getInstance();
$conn = $db->getConnection(); 