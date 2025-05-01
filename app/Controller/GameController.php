<?php
class GameController extends BaseController {
    private $gameModel;
    private $categoryModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->gameModel = $this->loadModel('GameModel');
        $this->categoryModel = $this->loadModel('CategoryModel');
    }

    public function index() {
        try {
            $games = $this->gameModel->getAllGames();
            $categories = $this->categoryModel->getAllCategories();
            
            $this->view('shopgame/shoppage', [
                'title' => 'Danh sách game',
                'games' => $games,
                'categories' => $categories,
                'css_files' => ['shoppage']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function detail($id) {
        try {
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                $this->view('error/404');
                return;
            }
            
            $this->view('game/detail', [
                'title' => $game['name'],
                'game' => $game,
                'css_files' => ['game']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function search() {
        try {
            $keyword = $_GET['keyword'] ?? '';
            $games = $this->gameModel->searchGames($keyword);
            
            $this->view('game/search', [
                'title' => 'Tìm kiếm game',
                'games' => $games,
                'keyword' => $keyword,
                'css_files' => ['game']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function category($categoryId) {
        try {
            $games = $this->gameModel->getGamesByCategory($categoryId);
            
            $this->view('game/category', [
                'title' => 'Danh mục game',
                'games' => $games,
                'categoryId' => $categoryId,
                'css_files' => ['game']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
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