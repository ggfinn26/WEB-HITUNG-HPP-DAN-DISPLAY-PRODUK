<?php

namespace App{
    use App\Admin;

    interface AdminInterface{
        public function login(string $email, string $password): ?Admin;
        public function resetPasswordAdmin(string $email, string $newPassword): void;

        public function findById(int $id): ?Admin;
        public function findByEmail(string $email): ?Admin;
    }
}