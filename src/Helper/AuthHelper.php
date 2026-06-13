<?php
namespace App\Helper;

class AuthHelper {
    /**
     * Require admin to be logged in to access a page
     */
    public static function requireAdmin() {
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: ?page=auth&action=login");
            exit;
        }
    }
}
