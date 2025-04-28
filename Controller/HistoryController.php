<?php
class HistoryController {
    private $historyModel;
    private $gameModel;
    private $userModel;

    public function __construct($conn) {
        $this->historyModel = new HistoryModel($conn);
        $this->gameModel = new GameModel($conn);
        $this->userModel = new UserModel($conn);
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

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    include('../model/db_connection.php');
    
    $historyController = new HistoryController($conn);
    $response = [];

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = ['success' => false, 'message' => 'Please login first'];
    } else {
        $user_id = $_SESSION['user_id'];

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'user_history':
                    $limit = $_GET['limit'] ?? 10;
                    $offset = $_GET['offset'] ?? 0;
                    $response = $historyController->getUserHistory($user_id, $limit, $offset);
                    break;
                case 'game_history':
                    if (isset($_GET['game_id'])) {
                        $response = $historyController->getGameHistory($_GET['game_id']);
                    }
                    break;
                case 'stats':
                    $response = $historyController->getPurchaseStats($user_id);
                    break;
                case 'recent':
                    $limit = $_GET['limit'] ?? 5;
                    $response = $historyController->getRecentPurchases($user_id, $limit);
                    break;
            }
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?> 