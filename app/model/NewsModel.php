<?php
class NewsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllNews($limit = 10, $offset = 0) {
        $query = "SELECT * FROM news ORDER BY published_at DESC LIMIT ?, ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $news = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $news[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $news;
    }

    public function getTotalNews() {
        $query = "SELECT COUNT(*) as total FROM news";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    public function getNewsById($id) {
        $query = "SELECT * FROM news WHERE news_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $news = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $news;
    }

    public function getRecentNews($limit = 5) {
        $query = "SELECT * FROM news ORDER BY published_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $news = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $news[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $news;
    }

    public function addNews($data) {
        $query = "INSERT INTO news (title, content, image_url, created_by, created_at, published_at) 
                 VALUES (?, ?, ?, ?, NOW(), ?)";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", 
            $data['title'],
            $data['content'],
            $data['image_url'],
            $data['created_by'],
            $data['published_at']
        );
        
        $success = mysqli_stmt_execute($stmt);
        $newsId = $success ? mysqli_insert_id($this->conn) : 0;
        mysqli_stmt_close($stmt);
        return $newsId;
    }

    public function updateNews($id, $data) {
        $query = "UPDATE news SET 
                 title = ?,
                 content = ?,
                 image_url = ?,
                 modified_by = ?,
                 modified_at = NOW(),
                 published_at = ?
                 WHERE news_id = ?";
                 
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi",
            $data['title'],
            $data['content'],
            $data['image_url'],
            $data['modified_by'],
            $data['published_at'],
            $id
        );
        
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function deleteNews($id) {
        $query = "DELETE FROM news WHERE news_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function searchNews($keyword) {
        $keyword = "%$keyword%";
        $stmt = mysqli_prepare($this->conn, "
            SELECT n.*, u.username as author
            FROM news n
            JOIN users u ON n.modified_by = u.user_id
            WHERE n.title LIKE ? OR n.content LIKE ?
            ORDER BY n.published_at DESC
        ");
        mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $news = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $news[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $news;
    }
}
?> 