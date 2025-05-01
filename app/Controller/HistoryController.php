<?php
class HistoryController extends BaseController {
    private $historyModel;
    private $gameModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->historyModel = $this->loadModel('HistoryModel');
        $this->gameModel = $this->loadModel('GameModel');
    }

    public function index() {
        try {
            $userId = $this->requireLogin();
            $histories = $this->historyModel->getUserHistory($userId);
            
            foreach ($histories as &$history) {
                $game = $this->gameModel->getGameById($history['game_id']);
                $history['game'] = $game;
            }
            
            $this->view('history/index', [
                'title' => 'Lịch sử mua hàng',
                'histories' => $histories,
                'css_files' => ['history']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
    }

    public function detail($id) {
        try {
            $userId = $this->requireLogin();
            $history = $this->historyModel->getHistoryById($id);
            
            if (!$history || $history['user_id'] != $userId) {
                $this->view('error/404');
                return;
            }
            
            $game = $this->gameModel->getGameById($history['game_id']);
            $history['game'] = $game;
            
            $this->view('history/detail', [
                'title' => 'Chi tiết đơn hàng',
                'history' => $history,
                'css_files' => ['history']
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view('error/404');
        }
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