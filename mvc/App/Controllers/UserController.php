<?php
class UserController
{
    public function index()
    {
        include __DIR__ . '/../Views/User/index.php';
    }
    public function create()
    {
        echo "U in method Create of UserController Controoler";
    }
}
