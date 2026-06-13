<?php

namespace App;

require_once __DIR__ . '/../Service/AdminServiceInterface.php';
require_once __DIR__ . '/../Exception/ValidationException.php';

use App\AdminServiceInterface;
use App\ValidationException;

class AuthController {
    private AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService) {
        $this->adminService = $adminService;
    }

    public function loginView() {
        if (isset($_SESSION['admin_logged_in'])) {
            header("Location: ?page=orders");
            return;
        }

        $csrf = csrf_token();

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $title = "Login Admin | Jastip Arunga";
        
        ob_start();
        require __DIR__ . '/../Views/Auth/login.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function loginProcess(array $postData) {
        if (!csrf_verify($postData)) {
            $_SESSION['error'] = "Invalid CSRF Token. Silakan coba lagi.";
            header("Location: ?page=auth&action=login");
            return;
        }

        $email = $postData['email'] ?? '';
        $password = $postData['password'] ?? '';

        try {
            $admin = $this->adminService->login($email, $password);
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $admin->getName();
            $_SESSION['admin_email'] = $admin->getEmail();

            header("Location: ?page=orders");
            return;
        } catch (ValidationException $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ?page=auth&action=login");
            return;
        }
    }

    public function logout() {
        $this->adminService->logOut();
        header("Location: ?page=home");
        return;
    }
}
