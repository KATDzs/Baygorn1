<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)));
}

class AdminController extends BaseController {
    private $userModel;
    private $gameModel;
    private $orderModel;
    private $categoryModel;
    private $newsModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->userModel = $this->loadModel('UserModel');
        $this->gameModel = $this->loadModel('GameModel');
        $this->orderModel = $this->loadModel('OrderModel');
        $this->categoryModel = $this->loadModel('CategoryModel');
        $this->newsModel = $this->loadModel('NewsModel');
    }

    // Kiểm tra quyền admin
    private function checkAdminAuth() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: /Baygorn1/auth/login');
                exit;
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Unauthorized access']);
            }
        }
    }

    // Trang dashboard
    public function index() {
        try {
            $this->checkAdminAuth();
            $stats = $this->getDashboardStats();
            
            $this->view('admin/dashboard', [
                'title' => 'Dashboard',
                'stats' => $stats,
                'css_files' => ['admin']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Quản lý người dùng
    public function users() {
        try {
            $this->checkAdminAuth();
            
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAllUsers($limit, $offset);
        $total = $this->userModel->getTotalUsers();
        $totalPages = ceil($total / $limit);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->view('admin/users', [
                    'title' => 'Quản lý người dùng',
                    'users' => $users,
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'css_files' => ['admin']
                ]);
        } else {
                $this->json([
            'success' => true,
            'data' => [
                'users' => $users,
                    'total' => $total,
                    'totalPages' => $totalPages
                ]
            ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Quản lý game
    public function games() {
        try {
            $this->checkAdminAuth();
            
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $games = $this->gameModel->getAllGames($limit, $offset);
        $total = $this->gameModel->getTotalGames();
        $totalPages = ceil($total / $limit);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->view('admin/games', [
                    'title' => 'Quản lý game',
                    'games' => $games,
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'css_files' => ['admin']
                ]);
        } else {
                $this->json([
                'success' => true,
                'data' => [
                    'games' => $games,
                    'total' => $total,
                    'totalPages' => $totalPages
                ]
            ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    // Thêm game mới
    public function addGame() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once ROOT_PATH . '/app/view/admin/game-form.php';
            return;
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $detailDesc = $_POST['detail_desc'] ?? '';
        $platform = $_POST['platform'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $imageUrl = $_POST['image_url'] ?? '';
        $categories = $_POST['categories'] ?? [];

        if (empty($title) || empty($description)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Title and description are required']);
            return;
        }

        $gameId = $this->gameModel->addGame([
            'title' => $title,
            'description' => $description,
            'detail_desc' => $detailDesc,
            'platform' => $platform,
            'price' => $price,
            'stock' => $stock,
            'image_url' => $imageUrl,
            'modified_by' => $_SESSION['user_id']
        ]);

        if ($gameId) {
            // Add categories
            foreach ($categories as $categoryId) {
                $this->gameModel->addGameCategory($gameId, $categoryId, $_SESSION['user_id']);
            }
            $this->sendJsonResponse(['success' => true, 'message' => 'Game added successfully']);
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Failed to add game']);
        }
    }

    // Cập nhật game
    public function editGame($id = null) {
        if (!$id) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Game ID is required']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $detailDesc = $_POST['detail_desc'] ?? '';
            $platform = $_POST['platform'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $imageUrl = $_POST['image_url'] ?? '';
            $categories = $_POST['categories'] ?? [];

            if (empty($title) || empty($description)) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Title and description are required']);
                return;
            }

            $success = $this->gameModel->updateGame($id, [
                'title' => $title,
                'description' => $description,
                'detail_desc' => $detailDesc,
                'platform' => $platform,
                'price' => $price,
                'stock' => $stock,
                'image_url' => $imageUrl,
                'modified_by' => $_SESSION['user_id']
            ]);

            if ($success) {
                // Update categories
                $this->gameModel->updateGameCategories($id, $categories, $_SESSION['user_id']);
                $this->sendJsonResponse(['success' => true, 'message' => 'Game updated successfully']);
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Failed to update game']);
            }
        } else {
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                require_once 'view/error/404.php';
                return;
            }
            require_once ROOT_PATH . '/app/view/admin/game-form.php';
        }
    }

    // Xóa game
    public function deleteGame($id = null) {
        if (!$id) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Game ID is required']);
            return;
        }

        if ($this->gameModel->deleteGame($id)) {
            $this->sendJsonResponse(['success' => true, 'message' => 'Game deleted successfully']);
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Failed to delete game']);
        }
    }

    // Thống kê dashboard
    private function getDashboardStats() {
        return [
            'total_users' => $this->userModel->getTotalUsers(),
            'total_games' => $this->gameModel->getTotalGames(),
            'total_orders' => $this->orderModel->getTotalOrders(),
            'total_revenue' => $this->orderModel->getTotalRevenue(),
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'top_games' => $this->gameModel->getTopSellingGames(5)
        ];
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}