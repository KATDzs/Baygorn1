<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, username, email, full_name, is_admin, created_at FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT user_id, username, email, full_name, is_admin, created_at FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createUser($username, $email, $password_hash, $full_name) {
        $created_at = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash, full_name, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $password_hash, $full_name, $created_at);
        return $stmt->execute();
    }

    public function updateUser($user_id, $email, $full_name) {
        $stmt = $this->conn->prepare("UPDATE users SET email = ?, full_name = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $email, $full_name, $user_id);
        return $stmt->execute();
    }

    public function updatePassword($user_id, $password_hash) {
        $stmt = $this->conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $stmt->bind_param("si", $password_hash, $user_id);
        return $stmt->execute();
    }

    public function deleteUser($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    public function getAllUsers($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("
            SELECT user_id, username, email, full_name, is_admin, created_at 
            FROM users 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalUsers() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function updateUserStatus($user_id, $is_admin) {
        $stmt = $this->conn->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $is_admin, $user_id);
        return $stmt->execute();
    }

    public function checkUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?>