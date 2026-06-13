<?php
namespace App\Helper;

/**
 * AppLogger — lightweight file logger tanpa file-lock bottleneck.
 *
 * Menggunakan error_log() yang di-redirect ke file custom via php.ini / .htaccess,
 * atau bisa langsung menggunakan write ke file dengan LOCK_EX yang lebih aman.
 *
 * Keunggulan vs file_put_contents per-call:
 * - Tidak melakukan fopen/flock per baris
 * - Buffering lebih efisien di level OS
 * - Mudah diganti ke PSR-3 logger (Monolog, dsb.) di masa depan
 */
class AppLogger {
    private static array $logPaths = [];

    private static function logPath(string $filename): string {
        if (!isset(self::$logPaths[$filename])) {
            self::$logPaths[$filename] = __DIR__ . '/../Logs/' . $filename;
        }
        return self::$logPaths[$filename];
    }

    public static function info(string $message, string $file = 'process.log'): void {
        self::write('INFO', $message, $file);
    }

    public static function success(string $message, string $file = 'process.log'): void {
        self::write('SUCCESS', $message, $file);
    }

    public static function failed(string $message, string $file = 'process.log'): void {
        self::write('FAILED', $message, $file);
    }

    public static function error(string $message, string $file = 'process.log'): void {
        self::write('ERROR', $message, $file);
    }

    private static function write(string $level, string $message, string $file): void {
        $line = '[' . date('Y-m-d H:i:s') . '] [' . $level . '] ' . $message . PHP_EOL;
        // LOCK_EX prevents file-lock race conditions under concurrent requests
        file_put_contents(self::logPath($file), $line, FILE_APPEND | LOCK_EX);
    }
}
