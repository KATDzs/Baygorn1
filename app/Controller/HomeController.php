<?php
class HomeController extends BaseController {
    private $gameModel;
    private $newsModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->gameModel = $this->loadModel('GameModel');
        $this->newsModel = $this->loadModel('NewsModel');
    }

    // Trang chủ
    public function index() {
        try {
            // Lấy game mới nhất
            $latestGames = $this->gameModel->getLatestGames(4);
            
            // Lấy tin tức mới nhất cho trang chủ
            $latestNews = $this->newsModel->getRecentNews(5);
            
            // Truyền dữ liệu vào view
            $this->view('home/home', [
                'title' => 'Trang chủ',
                'latestGames' => $latestGames,
                'latestNews' => $latestNews,
                'css_files' => ['home']
            ]);
            
        } catch (Exception $e) {
            // Log lỗi
            error_log($e->getMessage());
            // Hiển thị trang lỗi
            $this->view('error/404');
        }
    }

    // Trang About
    public function about() {
        $this->view('home/about', [
            'title' => 'Về chúng tôi',
            'css_files' => ['about']
        ]);
    }

    // Trang Game
    public function games() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;

            $games = $this->gameModel->getAllGames($limit, $offset);
            $total = $this->gameModel->getTotalGames();
            $totalPages = ceil($total / $limit);

            $this->view('shopgame/shoppage', [
                'title' => 'Danh sách game',
                'games' => $games,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'css_files' => ['shoppage']
            ]);
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Chi tiết game
    public function game($id = null) {
        try {
            if (!$id) {
                $this->redirect('games');
            }

            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                $this->view('error/404');
                return;
            }

            // Lấy game liên quan
            $relatedGames = $this->gameModel->getRelatedGames($id, 4);
            
            $this->view('shopgame/detail', [
                'title' => $game['name'],
                'game' => $game,
                'relatedGames' => $relatedGames,
                'css_files' => ['shoppage']
            ]);
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }
} 