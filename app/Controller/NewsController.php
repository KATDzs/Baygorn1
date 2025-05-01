<?php
class NewsController {
    private $newsModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/NewsModel.php';
        
        global $conn;
        $this->newsModel = new NewsModel($conn);
    }

    // Danh sách tin tức
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getAllNews($limit, $offset);
        $total = $this->newsModel->getTotalNews();
        $totalPages = ceil($total / $limit);

        require_once 'view/layout/header.php';
        require_once 'view/news/news.php';
        require_once 'view/layout/footer.php';
    }

    // Chi tiết tin tức
    public function detail($id = null) {
        if (!$id) {
            header('Location: /Baygorn1/news');
            exit;
        }

        $newsItem = $this->newsModel->getNewsById($id);
        if (!$newsItem) {
            require_once 'view/error/404.php';
            return;
        }

        // Lấy tin tức liên quan
        $relatedNews = $this->newsModel->getRecentNews(4);
        
        require_once 'view/layout/header.php';
        // Dựa vào ID để load view tương ứng
        if ($id == 1) {
            require_once 'view/news/news1.php';
        } else if ($id == 2) {
            require_once 'view/news/news2.php';
        } else if ($id == 3) {
            require_once 'view/news/news3.php';
        } else {
            require_once 'view/news/news.php';
        }
        require_once 'view/layout/footer.php';
    }

    // API lấy danh sách tin tức
    public function getNews() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getAllNews($limit, $offset);
        $total = $this->newsModel->getTotalNews();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => [
                'news' => $news,
                'total' => $total,
                'page' => $page,
                'totalPages' => ceil($total / $limit)
            ]
        ]);
    }

    // API lấy chi tiết tin tức
    public function getNewsDetail($id) {
        $newsItem = $this->newsModel->getNewsById($id);
        
        if (!$newsItem) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'News not found'
            ]);
            return;
        }

        $relatedNews = $this->newsModel->getRecentNews(4);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => [
                'news' => $newsItem,
                'related' => $relatedNews
            ]
        ]);
    }
} 