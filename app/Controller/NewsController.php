<?php
class NewsController extends BaseController {
    private $newsModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->newsModel = $this->loadModel('NewsModel');
    }

    // Danh sách tin tức
    public function index() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $news = $this->newsModel->getAllNews($limit, $offset);
            $total = $this->newsModel->getTotalNews();
            $totalPages = ceil($total / $limit);

            $this->view('news/news', [
                'title' => 'Tin tức',
                'news' => $news,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'css_files' => ['news']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Chi tiết tin tức
    public function detail($id = null) {
        try {
            if (!$id) {
                $this->redirect('news');
            }

            $newsItem = $this->newsModel->getNewsById($id);
            if (!$newsItem) {
                $this->view('error/404');
                return;
            }

            // Lấy tin tức liên quan
            $relatedNews = $this->newsModel->getRecentNews(4);
            
            $this->view('news/detail', [
                'title' => $newsItem['title'],
                'newsItem' => $newsItem,
                'relatedNews' => $relatedNews,
                'css_files' => ['news']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // API lấy danh sách tin tức
    public function getNews() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;

            $news = $this->newsModel->getAllNews($limit, $offset);
            $total = $this->newsModel->getTotalNews();
            
            $this->json([
                'status' => 'success',
                'data' => [
                    'news' => $news,
                    'total' => $total,
                    'page' => $page,
                    'totalPages' => ceil($total / $limit)
                ]
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ]);
        }
    }

    // API lấy chi tiết tin tức
    public function getNewsDetail($id) {
        try {
            $newsItem = $this->newsModel->getNewsById($id);
            
            if (!$newsItem) {
                $this->json([
                    'status' => 'error',
                    'message' => 'News not found'
                ]);
                return;
            }

            $relatedNews = $this->newsModel->getRecentNews(4);
            
            $this->json([
                'status' => 'success',
                'data' => [
                    'news' => $newsItem,
                    'related' => $relatedNews
                ]
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ]);
        }
    }
} 