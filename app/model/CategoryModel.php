<?php
class CategoryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY name ASC";
        $result = mysqli_query($this->conn, $query);
        
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        
        return $categories;
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE category_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $category = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $category;
    }

    public function getCategoryByName($name) {
        $query = "SELECT * FROM categories WHERE name = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $category = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $category;
    }

    public function addCategory($name, $description = null) {
        $query = "INSERT INTO categories (name, description, created_at) VALUES (?, ?, NOW())";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $name, $description);
        $success = mysqli_stmt_execute($stmt);
        $categoryId = $success ? mysqli_insert_id($this->conn) : 0;
        mysqli_stmt_close($stmt);
        return $categoryId;
    }

    public function updateCategory($id, $name, $description = null) {
        $query = "UPDATE categories SET name = ?, description = ?, modified_at = NOW() WHERE category_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function deleteCategory($id) {
        mysqli_begin_transaction($this->conn);
        
        try {
            // Delete game categories first
            $query1 = "DELETE FROM game_categories WHERE category_id = ?";
            $stmt1 = mysqli_prepare($this->conn, $query1);
            mysqli_stmt_bind_param($stmt1, "i", $id);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);
            
            // Then delete the category
            $query2 = "DELETE FROM categories WHERE category_id = ?";
            $stmt2 = mysqli_prepare($this->conn, $query2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            $success = mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            mysqli_commit($this->conn);
            return $success;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }

    public function getGameCategories($gameId) {
        $query = "SELECT c.* 
                 FROM categories c
                 JOIN game_categories gc ON c.category_id = gc.category_id
                 WHERE gc.game_id = ?
                 ORDER BY c.name ASC";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $gameId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $categories;
    }

    public function addGameToCategory($game_id, $category_id, $modified_by) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO game_categories (game_id, category_id, modified_by) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $game_id, $category_id, $modified_by);
        return mysqli_stmt_execute($stmt);
    }

    public function removeGameFromCategory($game_id, $category_id) {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM game_categories WHERE game_id = ? AND category_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $game_id, $category_id);
        return mysqli_stmt_execute($stmt);
    }

    public function checkCategoryExists($name) {
        $stmt = mysqli_prepare($this->conn, "SELECT category_id FROM categories WHERE name = ?");
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }
}
?> 