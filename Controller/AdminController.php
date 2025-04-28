<?php
class AdminController {
    private $userModel;
    private $gameModel;
    private $orderModel;
    private $categoryModel;
    private $newsModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
        $this->gameModel = new GameModel($conn);
        $this->orderModel = new OrderModel($conn);
        $this->categoryModel = new CategoryModel($conn);
        $this->newsModel = new NewsModel($conn);
    }

    // User Management
    public function getAllUsers($limit = 10, $offset = 0) {
        $users = $this->userModel->getAllUsers($limit, $offset);
        $total = $this->userModel->getTotalUsers();
        return [
            'success' => true,
            'data' => [
                'users' => $users,
                'total' => $total
            ]
        ];
    }

    public function updateUserStatus($user_id, $is_admin) {
        if ($this->userModel->updateUserStatus($user_id, $is_admin)) {
            return ['success' => true, 'message' => 'User status updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update user status'];
    }

    // Game Management
    public function addGame($title, $description, $detail_desc, $platform, $price, $stock, $image_url, $categories) {
        $game_id = $this->gameModel->addGame($title, $description, $detail_desc, $platform, $price, $stock, $image_url);
        if ($game_id) {
            foreach ($categories as $category_id) {
                $this->gameModel->addGameCategory($game_id, $category_id);
            }
            return ['success' => true, 'message' => 'Game added successfully', 'data' => ['game_id' => $game_id]];
        }
        return ['success' => false, 'message' => 'Failed to add game'];
    }

    public function updateGame($game_id, $title, $description, $detail_desc, $platform, $price, $stock, $image_url, $categories) {
        if ($this->gameModel->updateGame($game_id, $title, $description, $detail_desc, $platform, $price, $stock, $image_url)) {
            $this->gameModel->updateGameCategories($game_id, $categories);
            return ['success' => true, 'message' => 'Game updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update game'];
    }

    public function deleteGame($game_id) {
        if ($this->gameModel->deleteGame($game_id)) {
            return ['success' => true, 'message' => 'Game deleted successfully'];
        }
        return ['success' => false, 'message' => 'Failed to delete game'];
    }

    // Order Management
    public function getAllOrders($limit = 10, $offset = 0) {
        $orders = $this->orderModel->getAllOrders($limit, $offset);
        $total = $this->orderModel->getTotalOrders();
        return [
            'success' => true,
            'data' => [
                'orders' => $orders,
                'total' => $total
            ]
        ];
    }

    public function updateOrderStatus($order_id, $status) {
        if ($this->orderModel->updateOrderStatus($order_id, $status)) {
            return ['success' => true, 'message' => 'Order status updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update order status'];
    }

    // Category Management
    public function addCategory($name, $description) {
        $category_id = $this->categoryModel->addCategory($name, $description);
        if ($category_id) {
            return ['success' => true, 'message' => 'Category added successfully', 'data' => ['category_id' => $category_id]];
        }
        return ['success' => false, 'message' => 'Failed to add category'];
    }

    public function updateCategory($category_id, $name, $description) {
        if ($this->categoryModel->updateCategory($category_id, $name, $description)) {
            return ['success' => true, 'message' => 'Category updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update category'];
    }

    public function deleteCategory($category_id) {
        if ($this->categoryModel->deleteCategory($category_id)) {
            return ['success' => true, 'message' => 'Category deleted successfully'];
        }
        return ['success' => false, 'message' => 'Failed to delete category'];
    }

    // News Management
    public function addNews($title, $content, $image_url) {
        $news_id = $this->newsModel->addNews($title, $content, $image_url);
        if ($news_id) {
            return ['success' => true, 'message' => 'News added successfully', 'data' => ['news_id' => $news_id]];
        }
        return ['success' => false, 'message' => 'Failed to add news'];
    }

    public function updateNews($news_id, $title, $content, $image_url) {
        if ($this->newsModel->updateNews($news_id, $title, $content, $image_url)) {
            return ['success' => true, 'message' => 'News updated successfully'];
        }
        return ['success' => false, 'message' => 'Failed to update news'];
    }

    public function deleteNews($news_id) {
        if ($this->newsModel->deleteNews($news_id)) {
            return ['success' => true, 'message' => 'News deleted successfully'];
        }
        return ['success' => false, 'message' => 'Failed to delete news'];
    }

    // Dashboard Statistics
    public function getDashboardStats() {
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'total_games' => $this->gameModel->getTotalGames(),
            'total_orders' => $this->orderModel->getTotalOrders(),
            'total_revenue' => $this->orderModel->getTotalRevenue(),
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'top_games' => $this->gameModel->getTopSellingGames(5)
        ];
        return ['success' => true, 'data' => $stats];
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include('db_connection.php');
    
    $adminController = new AdminController($conn);
    $response = [];

    // Check if user is admin
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        $response = ['success' => false, 'message' => 'Unauthorized access'];
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'users':
                        $limit = $_GET['limit'] ?? 10;
                        $offset = $_GET['offset'] ?? 0;
                        $response = $adminController->getAllUsers($limit, $offset);
                        break;
                    case 'orders':
                        $limit = $_GET['limit'] ?? 10;
                        $offset = $_GET['offset'] ?? 0;
                        $response = $adminController->getAllOrders($limit, $offset);
                        break;
                    case 'stats':
                        $response = $adminController->getDashboardStats();
                        break;
                }
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'update_user_status':
                        if (isset($_POST['user_id']) && isset($_POST['is_admin'])) {
                            $response = $adminController->updateUserStatus($_POST['user_id'], $_POST['is_admin']);
                        }
                        break;
                    case 'add_game':
                        $response = $adminController->addGame(
                            $_POST['title'],
                            $_POST['description'],
                            $_POST['detail_desc'],
                            $_POST['platform'],
                            $_POST['price'],
                            $_POST['stock'],
                            $_POST['image_url'],
                            $_POST['categories']
                        );
                        break;
                    case 'update_game':
                        if (isset($_POST['game_id'])) {
                            $response = $adminController->updateGame(
                                $_POST['game_id'],
                                $_POST['title'],
                                $_POST['description'],
                                $_POST['detail_desc'],
                                $_POST['platform'],
                                $_POST['price'],
                                $_POST['stock'],
                                $_POST['image_url'],
                                $_POST['categories']
                            );
                        }
                        break;
                    case 'delete_game':
                        if (isset($_POST['game_id'])) {
                            $response = $adminController->deleteGame($_POST['game_id']);
                        }
                        break;
                    case 'update_order_status':
                        if (isset($_POST['order_id']) && isset($_POST['status'])) {
                            $response = $adminController->updateOrderStatus($_POST['order_id'], $_POST['status']);
                        }
                        break;
                    case 'add_category':
                        $response = $adminController->addCategory($_POST['name'], $_POST['description']);
                        break;
                    case 'update_category':
                        if (isset($_POST['category_id'])) {
                            $response = $adminController->updateCategory($_POST['category_id'], $_POST['name'], $_POST['description']);
                        }
                        break;
                    case 'delete_category':
                        if (isset($_POST['category_id'])) {
                            $response = $adminController->deleteCategory($_POST['category_id']);
                        }
                        break;
                    case 'add_news':
                        $response = $adminController->addNews($_POST['title'], $_POST['content'], $_POST['image_url']);
                        break;
                    case 'update_news':
                        if (isset($_POST['news_id'])) {
                            $response = $adminController->updateNews($_POST['news_id'], $_POST['title'], $_POST['content'], $_POST['image_url']);
                        }
                        break;
                    case 'delete_news':
                        if (isset($_POST['news_id'])) {
                            $response = $adminController->deleteNews($_POST['news_id']);
                        }
                        break;
                }
            }
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?> 