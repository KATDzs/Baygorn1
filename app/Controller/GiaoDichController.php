<?php
require_once __DIR__ . '/../model/GameModel.php';

class GiaoDichController {
    private $gameModel;

    public function __construct() {
        require_once __DIR__ . '/../../core/db_connection.php';
        $this->gameModel = new GameModel($conn);
    }

    public function getGameDetail($gameId) {
        return $this->gameModel->getGameById($gameId);
    }
}
?> 