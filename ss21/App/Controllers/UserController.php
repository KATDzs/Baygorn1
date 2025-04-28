<?php
class UserController
{
    public function index()
    {
        include __DIR__ . '/../Views/User/index.php';
    }
    public function register()
    {
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        $error = '';
        $userModel = new UserModel();
        $config = require './config.php';
        $baseURL = $config['baseURL'];
    
    }
    
        public function createUser($fullname, $username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, username, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fullname, $username, $hashedPassword]);
        return $this->db->lastInsertId();
    }

    
}
