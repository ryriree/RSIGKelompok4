<?php
require_once __DIR__ . '/../models/MenuModel.php';

class MenuController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    public function index() {
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;
        $menus = $this->menuModel->getAllMenu($kategori);
        require_once __DIR__ . '/../views/pelanggan/melihat_menu.php';
    }
}
?>