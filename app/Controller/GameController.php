<?php
class GameController {
    private $gameModel;
    private $categoryModel;

    public function __construct() {
        // Không cần gọi session_start() ở đây vì đã được gọi trong index.php
        require_once 'core/db_connection.php';
        require_once 'model/GameModel.php';
        require_once 'model/CategoryModel.php';
        
        global $conn;
        $this->gameModel = new GameModel($conn);
        $this->categoryModel = new CategoryModel($conn);
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $games = $this->gameModel->getAllGames($limit, $offset);
        $totalGames = $this->gameModel->getTotalGames();
        $totalPages = ceil($totalGames / $limit);
        
        // Load layout
        require_once 'view/layout/header.php';
        require_once 'view/shopgame/shoppage.php';
        require_once 'view/layout/footer.php';
    }

    public function detail($id) {
        $game = $this->gameModel->getGameById($id);
        if (!$game) {
            header('Location: /Baygorn1/game');
            exit;
        }
        
        $relatedGames = $this->gameModel->getRelatedGames($id);
        
        // Load layout
        require_once 'view/layout/header.php';
        require_once 'view/shopgame/detail.php';
        require_once 'view/layout/footer.php';
    }

    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $category = $_GET['category'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $games = $this->gameModel->searchGames($keyword, $category, $limit, $offset);
        $totalGames = $this->gameModel->getSearchCount($keyword, $category);
        $totalPages = ceil($totalGames / $limit);
        
        $categories = $this->categoryModel->getAllCategories();
        
        // Load layout
        require_once 'view/layout/header.php';
        require_once 'view/shopgame/shoppage.php';
        require_once 'view/layout/footer.php';
    }

    public function add() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /Baygorn1/auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            $image = $_FILES['image'] ?? null;
            $imagePath = '';
            
            if ($image && $image['tmp_name']) {
                $imagePath = 'asset/img/' . uniqid() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'], $imagePath);
            }
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'detail_desc' => $_POST['detail_desc'] ?? '',
                'platform' => $_POST['platform'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'image_url' => $imagePath,
                'modified_by' => $_SESSION['user_id']
            ];
            
            $gameId = $this->gameModel->addGame($data);
            
            if ($gameId && isset($_POST['categories'])) {
                $this->gameModel->updateGameCategories($gameId, $_POST['categories'], $_SESSION['user_id']);
            }
            
            header('Location: /Baygorn1/game/detail/' . $gameId);
            exit;
        }
        
        $categories = $this->categoryModel->getAllCategories();
        
        // Load layout
        require_once 'view/layout/header.php';
        require_once 'view/shopgame/add.php';
        require_once 'view/layout/footer.php';
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /Baygorn1/auth/login');
            exit;
        }
        
        $game = $this->gameModel->getGameById($id);
        if (!$game) {
            header('Location: /Baygorn1/game');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            $image = $_FILES['image'] ?? null;
            $imagePath = $game['image_url'];
            
            if ($image && $image['tmp_name']) {
                if ($game['image_url']) {
                    unlink($game['image_url']);
                }
                $imagePath = 'asset/img/' . uniqid() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'], $imagePath);
            }
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'detail_desc' => $_POST['detail_desc'] ?? '',
                'platform' => $_POST['platform'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'image_url' => $imagePath,
                'modified_by' => $_SESSION['user_id']
            ];
            
            $this->gameModel->updateGame($id, $data);
            
            if (isset($_POST['categories'])) {
                $this->gameModel->updateGameCategories($id, $_POST['categories'], $_SESSION['user_id']);
            }
            
            header('Location: /Baygorn1/game/detail/' . $id);
            exit;
        }
        
        $categories = $this->categoryModel->getAllCategories();
        $gameCategories = $this->gameModel->getGameCategories($id);
        
        // Load layout
        require_once 'view/layout/header.php';
        require_once 'view/shopgame/edit.php';
        require_once 'view/layout/footer.php';
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $game = $this->gameModel->getGameById($id);
        if ($game && $game['image_url']) {
            unlink($game['image_url']);
        }
        
        $result = $this->gameModel->deleteGame($id);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Game deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete game']);
        }
    }

    public function getAllGames($limit = 0, $offset = 0) {
        $games = $this->gameModel->getAllGames($limit, $offset);
        $categories = $this->categoryModel->getAllCategories();

        return [
            'success' => true,
            'data' => [
                'games' => $games,
                'categories' => $categories
            ]
        ];
    }

    public function getGameById($game_id) {
        $game = $this->gameModel->getGameById($game_id);
        if (!$game) {
            return ['success' => false, 'message' => 'Game not found'];
        }

        $categories = $this->categoryModel->getGameCategories($game_id);

        return [
            'success' => true,
            'data' => [
                'game' => $game,
                'categories' => $categories
            ]
        ];
    }

    public function getGamesByCategory($category_id) {
        $games = $this->gameModel->getGamesByCategory($category_id);
        $category = $this->categoryModel->getCategoryById($category_id);

        if (!$category) {
            return ['success' => false, 'message' => 'Category not found'];
        }

        return [
            'success' => true,
            'data' => [
                'games' => $games,
                'category' => $category
            ]
        ];
    }

    public function searchGames($keyword) {
        $games = $this->gameModel->searchGames($keyword);
        return [
            'success' => true,
            'data' => $games
        ];
    }

    public function getFeaturedGames($limit = 5) {
        $games = $this->gameModel->getAllGames($limit);
        return [
            'success' => true,
            'data' => $games
        ];
    }

    public function api() {
        if (!isset($_GET['action'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'No action specified']);
            return;
        }

        switch ($_GET['action']) {
            case 'all':
                $limit = $_GET['limit'] ?? 0;
                $offset = $_GET['offset'] ?? 0;
                $response = $this->getAllGames($limit, $offset);
                break;
            case 'detail':
                if (!isset($_GET['id'])) {
                    $response = ['success' => false, 'message' => 'No game ID provided'];
                    break;
                }
                $response = $this->getGameById($_GET['id']);
                break;
            case 'category':
                if (!isset($_GET['id'])) {
                    $response = ['success' => false, 'message' => 'No category ID provided'];
                    break;
                }
                $response = $this->getGamesByCategory($_GET['id']);
                break;
            case 'search':
                if (!isset($_GET['keyword'])) {
                    $response = ['success' => false, 'message' => 'No search keyword provided'];
                    break;
                }
                $response = $this->searchGames($_GET['keyword']);
                break;
            case 'featured':
                $limit = $_GET['limit'] ?? 5;
                $response = $this->getFeaturedGames($limit);
                break;
            default:
                $response = ['success' => false, 'message' => 'Invalid action'];
        }

        $this->sendJsonResponse($response);
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
} 