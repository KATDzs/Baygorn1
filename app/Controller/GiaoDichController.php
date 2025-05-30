<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/GameModel.php';

class GiaoDichController extends BaseController {
    private $gameModel;

    public function __construct($conn) {
        parent::__construct($conn);
        $this->gameModel = new GameModel($conn);
    }

    public function getGameDetail($gameId) {
        return $this->gameModel->getGameById($gameId);
    }

    public function redirectPayment() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = "/Baygorn1/index.php?url=giaodich/redirectPayment&id=" . $_GET['id'];
            header("Location: /Baygorn1/index.php?url=auth/login");
            exit;
        }
        header("Location: /Baygorn1/app/view/giaodich/payment_confirmation.php?id=" . $_GET['id']);
        exit;
    }

    public function processPayment() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Baygorn1/app/view/auth/login.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game_id = $_GET['id'] ?? 0;
            $fullName = $_POST['fullName'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $paymentMethod = $_POST['paymentMethod'] ?? '';
            $user_id = $_SESSION['user']['user_id'] ?? $_SESSION['user_id'] ?? null;
            require_once __DIR__ . '/../model/HistoryModel.php';
            $historyModel = new \HistoryModel($this->conn);
            $game = $this->gameModel->getGameById($game_id);
            if (!$game) {
                header("Location: /Baygorn1/");
                exit;
            }
            if (isset($game['price']) && floatval($game['price']) == 0) {
                // Game free: mua ngay
                $historyModel->addToHistory($user_id, $game_id, null, 1, 0);
                // Có thể thêm thông báo thành công ở đây
                header("Location: /Baygorn1/index.php?url=user/history&msg=free_success");
                exit;
            } else {
                // Game trả phí: chuyển sang trang QR
                $_SESSION['pending_payment'] = [
                    'game_id' => $game_id,
                    'fullName' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'paymentMethod' => $paymentMethod
                ];
                header("Location: /Baygorn1/app/view/giaodich/show_qr.php?id=$game_id");
                exit;
            }
        }
    }
}
?>