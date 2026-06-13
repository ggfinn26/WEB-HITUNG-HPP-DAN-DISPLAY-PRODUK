<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

// Force HTTPS in production (skip on localhost/local IPs)
$localHosts = ['localhost', '127.0.0.1', '::1'];
if (!$isHttps && !in_array($_SERVER['SERVER_NAME'] ?? '', $localHosts, true)) {
    header('Location: https://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/'), true, 301);
    exit;
}

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Autoload dari Composer agar semua class App\* terbaca otomatis
require_once __DIR__ . '/../vendor/autoload.php';

use App\OrderRepositoryImpl;
use App\OrderService;
use App\OrderController;
use App\AdminRepositoryImpl;
use App\AdminService;
use App\AuthController;
use App\ProductRepositoryImpl;
use App\RincianHppRepositoryImpl;
use App\ProductService;
use App\HomeController;
use App\ProductController;
use App\RincianHppController;
use App\RincianHppService;
use App\PengeluaranRepositoryImpl;
use App\LaporanService;
use App\LaporanController;
use App\SesiJastipRepositoryImpl;
use App\SesiJastipService;
use App\SesiJastipController;

// Inisialisasi Dependensi (Manual Dependency Injection)
try {
    // Admin Module
    $adminRepository = new AdminRepositoryImpl();
    $adminService = new AdminService($adminRepository);
    $authController = new AuthController($adminService);

    // Product & HPP Module (harus sebelum Order karena OrderController membutuhkan kedua service ini)
    $productRepository = new ProductRepositoryImpl();
    $hppRepository = new RincianHppRepositoryImpl();
    $rincianHppService = new RincianHppService($hppRepository, $productRepository);
    $productService = new ProductService($productRepository, $hppRepository);
    $productController = new ProductController($productService, $rincianHppService);
    $rincianHppController = new RincianHppController($rincianHppService);

    // Order Module
    $orderRepository = new OrderRepositoryImpl();
    $orderService = new OrderService($orderRepository);
    $orderController = new OrderController($orderService, $productService, $rincianHppService);

    // Home Module
    $homeController = new HomeController($productService, $orderService);

    // Laporan Module
    $pengeluaranRepository = new PengeluaranRepositoryImpl();
    $laporanService = new LaporanService($orderRepository, $pengeluaranRepository);
    $laporanController = new LaporanController($laporanService);

    // Sesi Jastip Module
    $sesiRepository  = new SesiJastipRepositoryImpl();
    $sesiService     = new SesiJastipService($sesiRepository);
    $sesiController  = new SesiJastipController($sesiService, $hppRepository, $pengeluaranRepository);

} catch (\Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die("Koneksi Database Gagal.");
}

// Simple Router Logic berdasarkan parameter URL
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';


try {
    switch ($page) {
        case 'home':
            $homeController->index();
            break;
            
        case 'catalog':
            $homeController->catalog();
            break;
            
        case 'track':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $homeController->trackOrder();
            } else {
                header("Location: ?page=home");
            }
            break;

        case 'auth':
            if ($action === 'login') {
                $authController->loginView();
            } elseif ($action === 'loginProcess' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->loginProcess($_POST);
            } elseif ($action === 'logout') {
                $authController->logout();
            } else {
                header("Location: ?page=auth&action=login");
            }
            break;

        case 'orders':
            App\Helper\AuthHelper::requireAdmin();
            if ($action === 'index') {
                $orderController->index();
            } elseif ($action === 'create') {
                $orderController->create();
            } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderController->store($_POST);
            } elseif ($action === 'show' && isset($_GET['id'])) {
                $orderController->show($_GET['id']);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderController->update($_GET['id'], $_POST);
            } elseif ($action === 'editDetail' && isset($_GET['id'])) {
                $orderController->editDetail($_GET['id']);
            } elseif ($action === 'updateDetail' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderController->updateDetail($_GET['id'], $_POST);
            } elseif ($action === 'print' && isset($_GET['id'])) {
                $orderController->print($_GET['id']);
            } else {
                http_response_code(404);
                echo "404 Not Found - Action tidak valid untuk halaman Orders.";
            }
            break;
            
        case 'products':
            App\Helper\AuthHelper::requireAdmin();
            if ($action === 'index') {
                $productController->index();
            } elseif ($action === 'create') {
                $productController->create();
            } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $productController->store($_POST);
            } elseif ($action === 'edit' && isset($_GET['id'])) {
                $productController->edit((int)$_GET['id']);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $productController->update((int)$_GET['id'], $_POST);
            } elseif ($action === 'delete' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $productController->delete((int)$_GET['id'], $_POST);
            } else {
                http_response_code(404);
                echo "404 Not Found - Action tidak valid untuk halaman Products.";
            }
            break;
            
        case 'hpp':
            App\Helper\AuthHelper::requireAdmin();
            if ($action === 'create') {
                $rincianHppController->create();
            } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $rincianHppController->store($_POST);
            } elseif ($action === 'edit' && isset($_GET['id'])) {
                $rincianHppController->edit((int)$_GET['id']);
            } elseif ($action === 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $rincianHppController->updateHpp((int)$_GET['id'], $_POST);
            } elseif ($action === 'delete' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $rincianHppController->delete((int)$_GET['id'], $_POST);
            } else {
                $rincianHppController->index();
            }
            break;
            
        case 'sesi':
            App\Helper\AuthHelper::requireAdmin();
            if ($action === 'create') {
                $sesiController->create();
            } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $sesiController->store($_POST);
            } elseif ($action === 'tutup' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $sesiController->tutup((int)$_GET['id'], $_POST);
            } elseif ($action === 'hapus' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $sesiController->hapus((int)$_GET['id'], $_POST);
            } elseif ($action === 'detail' && isset($_GET['id'])) {
                $sesiController->detail((int)$_GET['id']);
            } else {
                $sesiController->index();
            }
            break;

        case 'laporan':
            App\Helper\AuthHelper::requireAdmin();
            if ($action === 'exportPdf') {
                $laporanController->exportPdf();
            } elseif ($action === 'pendapatan') {
                $laporanController->pendapatan();
            } elseif ($action === 'deleteOrder' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $laporanController->deleteOrder((int)$_GET['id'], $_POST);
            } elseif ($action === 'bulkDeleteOrders' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $laporanController->bulkDeleteOrders($_POST);
            } elseif ($action === 'storePengeluaran' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $laporanController->storePengeluaran($_POST);
            } elseif ($action === 'editPengeluaran' && isset($_GET['id'])) {
                $laporanController->editPengeluaran((int)$_GET['id']);
            } elseif ($action === 'updatePengeluaran' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $laporanController->updatePengeluaran((int)$_GET['id'], $_POST);
            } elseif ($action === 'deletePengeluaran' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $laporanController->deletePengeluaran((int)$_GET['id'], $_POST);
            } else {
                $laporanController->index();
            }
            break;

        default:
            http_response_code(404);
            echo "404 Not Found - Halaman '$page' tidak ditemukan di gerbang aplikasi.";
            break;
    }
} catch (\Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Terjadi kesalahan. Silakan coba lagi.";
}
