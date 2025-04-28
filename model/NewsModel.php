<?php
class NewsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllNews($limit = 0, $offset = 0) {
        $query = "
            SELECT n.*, u.username as author
            FROM news n
            JOIN users u ON n.modified_by = u.user_id
            ORDER BY n.published_at DESC
        ";
        
        if ($limit > 0) {
            $query .= " LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
        } else {
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNewsById($news_id) {
        $stmt = $this->conn->prepare("
            SELECT n.*, u.username as author
            FROM news n
            JOIN users u ON n.modified_by = u.user_id
            WHERE n.news_id = ?
        ");
        $stmt->bind_param("i", $news_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createNews($title, $content, $image_url, $modified_by) {
        $published_at = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("
            INSERT INTO news (title, content, image_url, published_at, modified_by)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssi", $title, $content, $image_url, $published_at, $modified_by);
        return $stmt->execute();
    }

    public function updateNews($news_id, $title, $content, $image_url, $modified_by) {
        $stmt = $this->conn->prepare("
            UPDATE news
            SET title = ?, content = ?, image_url = ?, modified_by = ?
            WHERE news_id = ?
        ");
        $stmt->bind_param("sssii", $title, $content, $image_url, $modified_by, $news_id);
        return $stmt->execute();
    }

    public function deleteNews($news_id) {
        $stmt = $this->conn->prepare("DELETE FROM news WHERE news_id = ?");
        $stmt->bind_param("i", $news_id);
        return $stmt->execute();
    }

    public function getRecentNews($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT n.*, u.username as author
            FROM news n
            JOIN users u ON n.modified_by = u.user_id
            ORDER BY n.published_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function searchNews($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("
            SELECT n.*, u.username as author
            FROM news n
            JOIN users u ON n.modified_by = u.user_id
            WHERE n.title LIKE ? OR n.content LIKE ?
            ORDER BY n.published_at DESC
        ");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalNews() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM news");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?> 