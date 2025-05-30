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
        try {
            $this->checkAdminAuth();
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $categories = $this->categoryModel->getAllCategories();
                require_once ROOT_PATH . '/app/view/admin/game-form.php';
                return;
            }

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $detailDesc = $_POST['detail_desc'] ?? '';
            $platform = $_POST['platform'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categories = $_POST['categories'] ?? [];
            // Xử lý upload ảnh nếu có
            $imageUrl = '';
            if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
                $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetDir = ROOT_PATH . '/asset/img/games/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $targetFile = $targetDir . $imgName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageUrl = $imgName;
                }
            }

            if (empty($title) || empty($description)) {
                $error = 'Vui lòng nhập đầy đủ tên game và mô tả.';
                $categories = $this->categoryModel->getAllCategories();
                require_once ROOT_PATH . '/app/view/admin/game-form.php';
                return;
            }

            $gameId = $this->gameModel->addGame([
                'title' => $title,
                'description' => $description,
                'detail_desc' => $detailDesc,
                'platform' => $platform,
                'price' => $price,
                'image_url' => $imageUrl,
                'modified_by' => $_SESSION['user_id']
            ]);

            if ($gameId) {
                foreach ($categories as $categoryId) {
                    $this->gameModel->addGameCategory($gameId, $categoryId, $_SESSION['user_id']);
                }
                header('Location: /Baygorn1/index.php?url=admin/games');
                exit;
            } else {
                $error = 'Thêm game thất bại.';
                $categories = $this->categoryModel->getAllCategories();
                require_once ROOT_PATH . '/app/view/admin/game-form.php';
            }
        } catch (Throwable $e) {
            echo '<b>EXCEPTION in addGame:</b> ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
            error_log('EXCEPTION in addGame: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            error_log($e->getTraceAsString());
        }
    }

    // Cập nhật game
    public function editGame($id = null) {
        $this->checkAdminAuth();
        // Nếu là POST mà $id không có, thử lấy từ POST['game_id']
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!$id || $id == 0)) {
            $id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : 0;
        }
        $id = (int)$id;
        if (!$id) {
            $error = 'ID game không hợp lệ.';
            require ROOT_PATH . '/app/view/error/404.php';
            return;
        }

        $game = $this->gameModel->getGameById($id);
        if (!$game) {
            $error = 'Không tìm thấy game với ID này.';
            require ROOT_PATH . '/app/view/error/404.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $detailDesc = trim($_POST['detail_desc'] ?? '');
            $platform = trim($_POST['platform'] ?? '');
            $price = $_POST['price'] ?? '';
            $categoriesSelected = $_POST['categories'] ?? [];

            $errors = [];
            if (empty($title)) $errors[] = 'Tên game không được để trống.';
            if (empty($description)) $errors[] = 'Mô tả không được để trống.';
            if (empty($platform)) $errors[] = 'Nền tảng không được để trống.';
            if (empty($categoriesSelected) || !is_array($categoriesSelected)) $errors[] = 'Vui lòng chọn ít nhất một danh mục.';
            if (!is_numeric($price) || $price < 0) $errors[] = 'Giá game phải là số không âm.';

            // Xử lý upload ảnh
            $imageUrl = $game['image_url'];
            if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = 'Chỉ cho phép upload file ảnh (jpg, png, gif, webp).';
                } else {
                    $oldImg = $game['image_url'];
                    if ($oldImg) {
                        if (strpos($oldImg, '/') === false) {
                            $oldImgPath = ROOT_PATH . '/asset/img/games/' . $oldImg;
                        } else {
                            $oldImgPath = ROOT_PATH . '/' . ltrim($oldImg, '/');
                        }
                        if (file_exists($oldImgPath)) {
                            @unlink($oldImgPath);
                        }
                    }
                    $imgName = uniqid() . '_' . basename($_FILES['image']['name']);
                    $targetDir = ROOT_PATH . '/asset/img/games/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    $targetFile = $targetDir . $imgName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $imageUrl = $imgName;
                    } else {
                        $errors[] = 'Tải ảnh lên thất bại.';
                    }
                }
            }

            if (count($errors) === 0) {
                $success = $this->gameModel->updateGame($id, [
                    'title' => $title,
                    'description' => $description,
                    'detail_desc' => $detailDesc,
                    'platform' => $platform,
                    'price' => $price,
                    'image_url' => $imageUrl,
                    'modified_by' => $_SESSION['user_id']
                ]);
                if ($success) {
                    $this->gameModel->updateGameCategories($id, $categoriesSelected, $_SESSION['user_id']);
                    header('Location: /Baygorn1/index.php?url=admin/games&msg=edit_success');
                    exit;
                } else {
                    $errors[] = 'Cập nhật game thất bại.';
                }
            }
            // Nếu có lỗi, giữ lại dữ liệu đã nhập và hiển thị lại form
            $categories = $this->categoryModel->getAllCategories();
            $game = [
                'game_id' => $id,
                'title' => $title,
                'description' => $description,
                'detail_desc' => $detailDesc,
                'platform' => $platform,
                'price' => $price,
                'image_url' => $imageUrl,
                'categories' => $categoriesSelected
            ];
            $error = implode('<br>', $errors);
            $id = $id; // Đảm bảo biến $id luôn tồn tại khi require view
            require ROOT_PATH . '/app/view/admin/game-form.php';
        } else {
            $categories = $this->categoryModel->getAllCategories();
            $game['categories'] = $this->categoryModel->getGameCategories($id);
            $error = '';
            require ROOT_PATH . '/app/view/admin/game-form.php';
        }
    }

    // Xóa game
    public function deleteGame($id = null) {
        if (!$id || !is_numeric($id) || $id <= 0) {
            header('Location: /Baygorn1/index.php?url=admin/games&msg=invalid_id');
            exit;
        }

        // Lấy thông tin game để xóa file ảnh nếu có
        $game = $this->gameModel->getGameById($id);
        if ($game && !empty($game['image_url'])) {
            $img = $game['image_url'];
            // Nếu image_url không chứa dấu '/' thì ghép với asset/img/games/
            if (strpos($img, '/') === false) {
                $imagePath = ROOT_PATH . '/asset/img/games/' . $img;
            } else {
                $imagePath = ROOT_PATH . '/' . ltrim($img, '/');
            }
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        if ($this->gameModel->deleteGame($id)) {
            header('Location: /Baygorn1/index.php?url=admin/games&msg=delete_success');
            exit;
        } else {
            header('Location: /Baygorn1/index.php?url=admin/games&msg=delete_fail');
            exit;
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