<?php

require_once __DIR__ . '/../Config/Database.php';
use App\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase{

    public function testConnection(){
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

}