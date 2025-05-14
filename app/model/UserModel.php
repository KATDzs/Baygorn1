<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
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

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }

    public function createUser($data) {
        // Kiểm tra trùng username/email
        if ($this->checkUsernameExists($data['username'])) {
            return 'duplicate_username';
        }
        if ($this->checkEmailExists($data['email'])) {
            return 'duplicate_email';
        }
        $query = "INSERT INTO users (username, email, password_hash, full_name, is_admin, created_at) 
                 VALUES (?, ?, ?, ?, 0, NOW())";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", 
            $data['username'],
            $data['email'],
            $data['password'],
            $data['full_name']
        );
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            error_log("SQL Error: " . mysqli_error($this->conn));
        }
        $userId = $success ? mysqli_insert_id($this->conn) : 0;
        mysqli_stmt_close($stmt);
        return $userId;
    }

    public function updateUser($id, $data) {
        $query = "UPDATE users SET 
                 email = ?,
                 full_name = ?,
                 phone = ?,
                 address = ?,
                 updated_at = NOW()
                 WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi",
            $data['email'],
            $data['full_name'],
            $data['phone'],
            $data['address'],
            $id
        );
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function updatePassword($id, $password_hash) {
        $query = "UPDATE users SET 
                 password_hash = ?,
                 modified_at = NOW()
                 WHERE user_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $password_hash, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function validateLogin($username, $password) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
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

    public function getLoginAttempts($username) {
        $query = "SELECT COUNT(*) as count, MAX(attempt_time) as last_attempt FROM login_attempts WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function resetLoginAttempts($username) {
        $query = "DELETE FROM login_attempts WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }

    public function incrementLoginAttempts($username) {
        $query = "INSERT INTO login_attempts (username, attempt_time) VALUES (?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }
}
?>