<?php

namespace App{
use App\Admin;
use App\AdminInterface;
use App\Database;
use App\Helper\AppLogger;
use PDO;

    class AdminRepositoryImpl implements AdminInterface{

        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function login(string $email, string $password): ?Admin{
            try{
                AppLogger::info('Login attempt: ' . $email, 'login.log');
                $stmt = $this->connection->prepare("SELECT * FROM admin WHERE email = ?");
                $stmt->execute([$email]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$result || !password_verify($password, $result['password_hash'])){
                    AppLogger::failed('Login gagal: ' . $email, 'login.log');
                    return null;
                }
                AppLogger::success('Login berhasil: ' . $email, 'login.log');
                return new Admin(
                    (int)$result['id'],
                    $result['name'],
                    $result['email'],
                    $result['password_hash'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at'])
                );
            } catch(\Throwable $e){
                AppLogger::error($e->getMessage(), 'login.log');
                throw $e;
            }
        }

        public function resetPasswordAdmin(string $email, string $newPassword): void{
            try{
                AppLogger::info('Reset password attempt: ' . $email, 'reset-password.log');
                $this->connection->beginTransaction();
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $this->connection->prepare("UPDATE admin SET password_hash = ? WHERE email = ?");
                $stmt->execute([$hashedPassword, $email]);
                if($stmt->rowCount() === 0){
                    $this->connection->rollBack();
                    AppLogger::failed('Email tidak ditemukan: ' . $email, 'reset-password.log');
                    throw new \App\ValidationException("Email admin tidak ditemukan");
                }
                $this->connection->commit();
                AppLogger::success('Reset password berhasil: ' . $email, 'reset-password.log');
            } catch(\Throwable $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                AppLogger::error($e->getMessage(), 'reset-password.log');
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