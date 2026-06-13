<?php

require_once __DIR__ . '/../Entity/Admin.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Repository/AdminInterface.php';
require_once __DIR__ . '/../Repository/AdminRepositoryImpl.php';
require_once __DIR__ . '/../Exception/ValidationException.php';

use PHPUnit\Framework\TestCase;
use App\Admin;
use App\Database;
use App\AdminRepositoryImpl;

class BuatAdminTest extends TestCase{

    private Admin $admin;
    private AdminRepositoryImpl $adminRepositoryImpl;

    public function setUp(): void{
        Database::getConnection()->exec("DELETE FROM admin");

        $plainPassword1 = "6T3DIF3p@";
        $stmt = Database::getConnection()->prepare("INSERT INTO admin (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute(["Ahnaf Nadewa", "baristasastra@gmail.com", password_hash($plainPassword1, PASSWORD_BCRYPT)]);

        $plainPassword2 = "Billa1998!";
        $stmt->execute(["Sabilla Bahana Jagad", "sabilabahanaj@gmail.com", password_hash($plainPassword2, PASSWORD_BCRYPT)]);

        $this->admin = new Admin();
        $this->adminRepositoryImpl = new AdminRepositoryImpl();
    }

    public function testBuatAdmin(): void{
        $this->admin->setId(1);
        $this->admin->setName("Ahnaf Nadewa");
        $this->admin->setPasswordHash(password_hash("6T3DIF3p@", PASSWORD_BCRYPT));
        $this->admin->setEmail("baristasastra@gmail.com");
        $this->admin->setCreatedAt(new \DateTime());
        $this->admin->setUpdatedAt(new \DateTime());

        self::assertEquals("Ahnaf Nadewa", $this->admin->getName());
        self::assertEquals("baristasastra@gmail.com", $this->admin->getEmail());
        self::assertTrue(password_verify("6T3DIF3p@", $this->admin->getPasswordHash()));

        $this->admin->setId(2);
        $this->admin->setName("Sabilla Bahana Jagad");
        $this->admin->setPasswordHash(password_hash("Billa1998!", PASSWORD_BCRYPT));
        $this->admin->setEmail("sabilabahanaj@gmail.com");
        $this->admin->setCreatedAt(new \DateTime());
        $this->admin->setUpdatedAt(new \DateTime());

        self::assertEquals("Sabilla Bahana Jagad", $this->admin->getName());
        self::assertEquals("sabilabahanaj@gmail.com", $this->admin->getEmail());
        self::assertTrue(password_verify("Billa1998!", $this->admin->getPasswordHash()));
    }

    public function testLogin(): void{
        $admin = $this->adminRepositoryImpl->login("baristasastra@gmail.com", "6T3DIF3p@");
        self::assertNotNull($admin);
        self::assertEquals("baristasastra@gmail.com", $admin->getEmail());
    }

    public function testLoginFailed(): void{
        $admin = $this->adminRepositoryImpl->login("baristasastra@gmail.com", "passwordsalah");
        self::assertNull($admin);
    }

    public function testResetPassword(): void{
        $this->adminRepositoryImpl->resetPasswordAdmin("baristasastra@gmail.com", "passwordBaru123!");

        $admin = $this->adminRepositoryImpl->login("baristasastra@gmail.com", "passwordBaru123!");
        self::assertNotNull($admin);
        self::assertEquals("baristasastra@gmail.com", $admin->getEmail());

        $adminLamaTidakBisa = $this->adminRepositoryImpl->login("baristasastra@gmail.com", "6T3DIF3p@");
        self::assertNull($adminLamaTidakBisa);
    }

    public function testResetPasswordEmailTidakDitemukan(): void{
        $this->expectException(\App\ValidationException::class);
        $this->expectExceptionMessage("Email admin tidak ditemukan");

        $this->adminRepositoryImpl->resetPasswordAdmin("emailtidakada@gmail.com", "passwordApapun");
    }
}
