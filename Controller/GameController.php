<?php
class GameController {
    private $gameModel;
    private $categoryModel;

    public function __construct($conn) {
        $this->gameModel = new GameModel($conn);
        $this->categoryModel = new CategoryModel($conn);
    }

    public function getAllGames($limit = 0, $offset = 0) {
        $games = $this->gameModel->getAllGames();
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

        // Get game categories
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
        // Get recent games as featured games
        $games = $this->gameModel->getAllGames($limit);
        return [
            'success' => true,
            'data' => $games
        ];
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include('db_connection.php');
    
    $gameController = new GameController($conn);
    $response = [];

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'all':
                $limit = $_GET['limit'] ?? 0;
                $offset = $_GET['offset'] ?? 0;
                $response = $gameController->getAllGames($limit, $offset);
                break;
            case 'detail':
                if (isset($_GET['id'])) {
                    $response = $gameController->getGameById($_GET['id']);
                }
                break;
            case 'category':
                if (isset($_GET['id'])) {
                    $response = $gameController->getGamesByCategory($_GET['id']);
                }
                break;
            case 'search':
                if (isset($_GET['keyword'])) {
                    $response = $gameController->searchGames($_GET['keyword']);
                }
                break;
            case 'featured':
                $limit = $_GET['limit'] ?? 5;
                $response = $gameController->getFeaturedGames($limit);
                break;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?> 