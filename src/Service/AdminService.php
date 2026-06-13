<?php

namespace App{
    use App\Admin;
    use App\AdminInterface;
    use App\ValidationException;
    use App\AdminServiceInterface;

    class AdminService implements AdminServiceInterface{

        private AdminInterface $adminRepository;

        public function __construct(AdminInterface $adminRepository){
            $this->adminRepository = $adminRepository;
        }

        public function login(string $email, string $password): Admin{
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $this->checkRateLimit($ip);

            $admin = $this->adminRepository->findByEmail($email);
            if($admin == null || !password_verify($password, $admin->getPasswordHash())){
                $this->recordFailedAttempt($ip);
                throw new ValidationException("Email atau password salah");
            }

            $this->clearAttempts($ip);
            return $admin;
        }

        public function resetPasswordAdmin(string $email, string $oldPassword, string $newPassword): void{
            $admin = $this->adminRepository->findByEmail($email);
            if($admin === null){
                throw new ValidationException("Email atau password salah");
            }
            if(!password_verify($oldPassword, $admin->getPasswordHash())){
                throw new ValidationException("Email atau password salah");
            }
            if(password_verify($newPassword, $admin->getPasswordHash())){
                throw new ValidationException("Password baru tidak boleh sama dengan password lama");
            }
            $this->adminRepository->resetPasswordAdmin($email, $newPassword);
        }

        public function generateCSRF(): string{
            return bin2hex(random_bytes(32));
        }

        public function checkPassword(string $password, string $hash): bool{
            return password_verify($password, $hash);
        }

        public function logOut(): void{
            session_destroy();
        }

        public function verifyCSRF(string $csrf): bool{
            return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $csrf);
        }

        private function checkRateLimit(string $ip): void{
            $data = $this->loadAttempts($ip);
            if($data['lockout_until'] > time()){
                $remaining = (int)ceil(($data['lockout_until'] - time()) / 60);
                throw new ValidationException("Terlalu banyak percobaan login. Coba lagi dalam {$remaining} menit.");
            }
        }

        private function recordFailedAttempt(string $ip): void{
            $data = $this->loadAttempts($ip);
            if($data['lockout_until'] > 0 && $data['lockout_until'] <= time()){
                $data = ['attempts' => 0, 'lockout_until' => 0];
            }
            $data['attempts']++;
            if($data['attempts'] >= 5){
                $data['lockout_until'] = time() + 900;
                $data['attempts'] = 0;
            }
            $this->saveAttempts($ip, $data);
        }

        private function clearAttempts(string $ip): void{
            $file = $this->attemptFile($ip);
            if(file_exists($file)) @unlink($file);
        }

        private function loadAttempts(string $ip): array{
            $file = $this->attemptFile($ip);
            if(!file_exists($file)) return ['attempts' => 0, 'lockout_until' => 0];
            return json_decode((string)file_get_contents($file), true) ?? ['attempts' => 0, 'lockout_until' => 0];
        }

        private function saveAttempts(string $ip, array $data): void{
            file_put_contents($this->attemptFile($ip), json_encode($data), LOCK_EX);
        }

        private function attemptFile(string $ip): string{
            return sys_get_temp_dir() . '/hpp_login_' . md5($ip) . '.json';
        }
    }
}
