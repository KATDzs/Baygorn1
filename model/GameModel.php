<?php
class GameModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllGames() {
        $stmt = $this->conn->prepare("SELECT * FROM games ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGameById($game_id) {
        $stmt = $this->conn->prepare("SELECT * FROM games WHERE game_id = ?");
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getGamesByCategory($category_id) {
        $stmt = $this->conn->prepare("
            SELECT g.* FROM games g
            JOIN game_categories gc ON g.game_id = gc.game_id
            WHERE gc.category_id = ?
            ORDER BY g.created_at DESC
        ");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createGame($title, $description, $detail_desc, $platform, $price, $stock, $image_url, $modified_by, $meta) {
        $created_at = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("
            INSERT INTO games (title, description, detail_desc, platform, price, stock, image_url, created_at, modified_by, meta)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssdisisi", $title, $description, $detail_desc, $platform, $price, $stock, $image_url, $created_at, $modified_by, $meta);
        return $stmt->execute();
    }

    public function updateGame($game_id, $title, $description, $detail_desc, $platform, $price, $stock, $image_url, $modified_by, $meta) {
        $stmt = $this->conn->prepare("
            UPDATE games 
            SET title = ?, description = ?, detail_desc = ?, platform = ?, price = ?, stock = ?, image_url = ?, modified_by = ?, meta = ?
            WHERE game_id = ?
        ");
        $stmt->bind_param("ssssdisisi", $title, $description, $detail_desc, $platform, $price, $stock, $image_url, $modified_by, $meta, $game_id);
        return $stmt->execute();
    }

    public function deleteGame($game_id) {
        $stmt = $this->conn->prepare("DELETE FROM games WHERE game_id = ?");
        $stmt->bind_param("i", $game_id);
        return $stmt->execute();
    }

    public function updateStock($game_id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE games SET stock = stock + ? WHERE game_id = ?");
        $stmt->bind_param("ii", $quantity, $game_id);
        return $stmt->execute();
    }

    public function searchGames($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("
            SELECT * FROM games 
            WHERE title LIKE ? OR description LIKE ? OR detail_desc LIKE ?
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?> 