<?php
namespace App\Helper;

class CsrfHelper {
    /**
     * Generate and return a CSRF token
     */
    public static function getToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify a CSRF token from request data
     */
    public static function verifyToken(array $data): bool {
        return !empty($data['csrf_token'])
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $data['csrf_token']);
    }
}
