<?php
class HistoryController {
    private $historyModel;
    private $gameModel;
    private $userModel;

    public function __construct() {
        require_once 'core/db_connection.php';
        require_once 'model/HistoryModel.php';
        require_once 'model/GameModel.php';
        require_once 'model/UserModel.php';
        
        global $conn;
        $this->historyModel = new HistoryModel($conn);
        $this->gameModel = new GameModel($conn);
        $this->userModel = new UserModel($conn);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: /Baygorn1/auth/login');
                exit;
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Please login first']);
            }
        }
        return $_SESSION['user_id'];
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function index() {
        $userId = $this->checkAuth();
        
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        $history = $this->historyModel->getUserHistory($userId, $limit, $offset);
        $total = $this->historyModel->getTotalPurchases($userId);
        $totalPages = ceil($total / $limit);
        
        require_once 'view/layout/header.php';
        require_once 'view/history/index.php';
        require_once 'view/layout/footer.php';
    }

    public function api() {
        $userId = $this->checkAuth();

        if (!isset($_GET['action'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'No action specified']);
            return;
        }

        switch ($_GET['action']) {
            case 'user_history':
                $limit = $_GET['limit'] ?? 10;
                $offset = $_GET['offset'] ?? 0;
                $response = $this->getUserHistory($userId, $limit, $offset);
                break;
            case 'game_history':
                if (!isset($_GET['game_id'])) {
                    $response = ['success' => false, 'message' => 'No game ID provided'];
                    break;
                }
                $response = $this->getGameHistory($_GET['game_id']);
                break;
            case 'stats':
                $response = $this->getPurchaseStats($userId);
                break;
            case 'recent':
                $limit = $_GET['limit'] ?? 5;
                $response = $this->getRecentPurchases($userId, $limit);
                break;
            default:
                $response = ['success' => false, 'message' => 'Invalid action'];
        }

        $this->sendJsonResponse($response);
    }

    public function getUserHistory($user_id, $limit = 10, $offset = 0) {
        $history = $this->historyModel->getUserHistory($user_id, $limit, $offset);
        $total = $this->historyModel->getTotalPurchases($user_id);

        return [
            'success' => true,
            'data' => [
                'history' => $history,
                'total' => $total
            ]
        ];
    }

    public function getGameHistory($game_id) {
        $history = $this->historyModel->getGameHistory($game_id);
        return [
            'success' => true,
            'data' => $history
        ];
    }

    public function getPurchaseStats($user_id) {
        $stats = $this->historyModel->getPurchaseStats($user_id);
        return [
            'success' => true,
            'data' => $stats
        ];
    }

    public function getRecentPurchases($user_id, $limit = 5) {
        $purchases = $this->historyModel->getRecentPurchases($user_id, $limit);
        return [
            'success' => true,
            'data' => $purchases
        ];
    }
} 