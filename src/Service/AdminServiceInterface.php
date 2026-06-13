<?php

namespace App{
    use App\Admin;

    interface AdminServiceInterface{
        public function login(string $email, string $password): Admin;
        public function resetPasswordAdmin(string $email, string $oldPassword, string $newPassword): void;
        public function checkPassword(string $password, string $hash): bool;
        public function logOut(): void;
        
    }
}