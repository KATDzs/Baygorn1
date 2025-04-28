<?php
class CategoryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($category_id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createCategory($name, $description) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    public function updateCategory($category_id, $name, $description) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, description = ? WHERE category_id = ?");
        $stmt->bind_param("ssi", $name, $description, $category_id);
        return $stmt->execute();
    }

    public function deleteCategory($category_id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        return $stmt->execute();
    }

    public function addGameToCategory($game_id, $category_id, $modified_by) {
        $stmt = $this->conn->prepare("INSERT INTO game_categories (game_id, category_id, modified_by) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $game_id, $category_id, $modified_by);
        return $stmt->execute();
    }

    public function removeGameFromCategory($game_id, $category_id) {
        $stmt = $this->conn->prepare("DELETE FROM game_categories WHERE game_id = ? AND category_id = ?");
        $stmt->bind_param("ii", $game_id, $category_id);
        return $stmt->execute();
    }

    public function getGameCategories($game_id) {
        $stmt = $this->conn->prepare("
            SELECT c.* FROM categories c
            JOIN game_categories gc ON c.category_id = gc.category_id
            WHERE gc.game_id = ?
        ");
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkCategoryExists($name) {
        $stmt = $this->conn->prepare("SELECT category_id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?> 