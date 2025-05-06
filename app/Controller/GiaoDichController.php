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

            // Xử lý thanh toán ở đây
            // TODO: Thêm logic xử lý thanh toán

            // Sau khi xử lý xong, chuyển về trang chủ
            header("Location: /Baygorn1/");
            exit;
        }
    }
}
?> 