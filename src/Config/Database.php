<?php

namespace App{

use PDO;
use PDOException;

class Database{
    private static ?\PDO $instance = null;

    static function getConnection(): \PDO{
        if(self::$instance !== null){
            return self::$instance;
        }

        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }

        $host     = $_ENV['DB_HOST']     ?? 'localhost';
        $port     = $_ENV['DB_PORT']     ?? 3306;
        $user     = $_ENV['DB_USER']     ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $database = $_ENV['DB_NAME']     ?? 'hitung_hpp';

        try{
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = $pdo;
            return self::$instance;
        }catch(PDOException $e){
            die("Koneksi Database Gagal.");
        }
    }
}
}
