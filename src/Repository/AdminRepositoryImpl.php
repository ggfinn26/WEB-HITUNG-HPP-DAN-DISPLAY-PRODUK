<?php

namespace App{
use App\Admin;
use App\AdminInterface;
use App\Database;
use PDO;

    class AdminRepositoryImpl implements AdminInterface{

        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function login(string $email, string $password): ?Admin{
            try{
                file_put_contents(__DIR__ . "/../Logs/login.log", "[INFO] Login attempt: " . $email . PHP_EOL, FILE_APPEND);
                $stmt = $this->connection->prepare("SELECT * FROM admin WHERE email = ?");
                $stmt->execute([$email]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$result || !password_verify($password, $result['password_hash'])){
                    file_put_contents(__DIR__ . "/../Logs/login.log", "[FAILED] Login gagal: " . $email . PHP_EOL, FILE_APPEND);
                    return null;
                }
                file_put_contents(__DIR__ . "/../Logs/login.log", "[SUCCESS] Login berhasil: " . $email . PHP_EOL, FILE_APPEND);
                return new Admin(
                    (int)$result['id'],
                    $result['name'],
                    $result['email'],
                    $result['password_hash'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at'])
                );
            } catch(\Throwable $e){
                file_put_contents(__DIR__ . "/../Logs/login.log", "[ERROR] " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                throw $e;
            }
        }

        public function resetPasswordAdmin(string $email, string $newPassword): void{
            try{
                file_put_contents(__DIR__ . "/../Logs/reset-password.log", "[INFO] Reset password attempt: " . $email . PHP_EOL, FILE_APPEND);
                $this->connection->beginTransaction();
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $this->connection->prepare("UPDATE admin SET password_hash = ? WHERE email = ?");
                $stmt->execute([$hashedPassword, $email]);
                if($stmt->rowCount() === 0){
                    $this->connection->rollBack();
                    file_put_contents(__DIR__ . "/../Logs/reset-password.log", "[FAILED] Email tidak ditemukan: " . $email . PHP_EOL, FILE_APPEND);
                    throw new \App\ValidationException("Email admin tidak ditemukan");
                }
                $this->connection->commit();
                file_put_contents(__DIR__ . "/../Logs/reset-password.log", "[SUCCESS] Reset password berhasil: " . $email . PHP_EOL, FILE_APPEND);
            } catch(\Throwable $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                file_put_contents(__DIR__ . "/../Logs/reset-password.log", "[ERROR] " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                throw $e;
            }
        }

        public function findById(int $id): ?Admin{
            try{
                $stmt = $this->connection->prepare("SELECT * FROM admin WHERE id = ?");
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$result){
                    return null;
                }
                return new Admin(
                    (int)$result['id'],
                    $result['name'],
                    $result['email'],
                    $result['password_hash'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at'])
                );
            } catch(\Throwable $e){
                throw $e;
            }
        }

        public function findByEmail(string $email): ?Admin{
            try{
                $stmt = $this->connection->prepare("SELECT * FROM admin WHERE email = ?");
                $stmt->execute([$email]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$result){
                    return null;
                }
                return new Admin(
                    (int)$result['id'],
                    $result['name'],
                    $result['email'],
                    $result['password_hash'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at'])
                );
            } catch(\Throwable $e){
                throw $e;
            }
        }
    }
}