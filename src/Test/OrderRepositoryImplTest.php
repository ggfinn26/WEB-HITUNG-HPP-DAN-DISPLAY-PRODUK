<?php

namespace App\Test;

require_once __DIR__ . '/../Entity/Order.php';
require_once __DIR__ . '/../Repository/OrderInterface.php';
require_once __DIR__ . '/../Repository/OrderRepositoryImpl.php';
require_once __DIR__ . '/../Config/Database.php';

use PHPUnit\Framework\TestCase;
use App\Order;
use App\OrderRepositoryImpl;
use App\Database;
use DateTime;
use PDOException;

class OrderRepositoryImplTest extends TestCase {
    private OrderRepositoryImpl $orderRepository;
    private Order $order;

    protected function setUp(): void {
        parent::setUp();
        
        $this->orderRepository = new OrderRepositoryImpl();
        
        Database::getConnection()->exec("DELETE FROM `order`");

        $this->order = new Order(
            0,
            "ORD-001",
            json_encode([["nama" => "Produk A", "qty" => 1]]),
            "150000.00",
            "PENDING",
            "John Doe",
            "Jalan Mawar",
            "081234567890",
            "@johndoe",
            new DateTime(),
            new DateTime()
        );
    }

    public function testSaveOrderSuccess() {
        $savedOrder = $this->orderRepository->saveOrder($this->order);
        self::assertGreaterThan(0, $savedOrder->getId());

        $allOrders = $this->orderRepository->findAll();
        self::assertCount(1, $allOrders);
        self::assertEquals("ORD-001", $allOrders[0]->getOrderNumber());
    }

    public function testSaveOrderFailed() {
        $this->orderRepository->saveOrder($this->order);

        $duplicateOrder = new Order(
            0,
            "ORD-001",
            json_encode([]),
            "1000.00",
            "PENDING",
            "Jane",
            "Alamat",
            "0811",
            "@jane",
            new DateTime(),
            new DateTime()
        );

        $this->expectException(PDOException::class);
        $this->orderRepository->saveOrder($duplicateOrder);
    }

    public function testUpdateOrderSuccess() {
        $savedOrder = $this->orderRepository->saveOrder($this->order);

        $savedOrder->setOrderStatus("COMPLETED");
        $savedOrder->setSubTotal("200000.00");
        $this->orderRepository->updateOrder($savedOrder);

        $updatedOrders = $this->orderRepository->findByOrderStatus("COMPLETED");
        self::assertCount(1, $updatedOrders);
        self::assertEquals("200000.00", $updatedOrders[0]->getSubTotal());
    }

    public function testUpdateOrderFailed() {
        $this->orderRepository->saveOrder($this->order);

        $secondOrder = clone $this->order;
        $secondOrder->setOrderNumber("ORD-002");
        $savedSecondOrder = $this->orderRepository->saveOrder($secondOrder);

        $savedSecondOrder->setOrderNumber("ORD-001");
        
        $this->expectException(PDOException::class);
        $this->orderRepository->updateOrder($savedSecondOrder);
    }

    public function testFindAll() {
        $this->orderRepository->saveOrder($this->order);

        $secondOrder = clone $this->order;
        $secondOrder->setOrderNumber("ORD-002");
        $this->orderRepository->saveOrder($secondOrder);

        $result = $this->orderRepository->findAll();
        self::assertCount(2, $result);
    }

    public function testFindByNamaPemesan() {
        $this->orderRepository->saveOrder($this->order);

        $secondOrder = clone $this->order;
        $secondOrder->setOrderNumber("ORD-002");
        $secondOrder->setNamaPemesan("Budi");
        $this->orderRepository->saveOrder($secondOrder);

        $result = $this->orderRepository->findByNamaPemesan("Budi");
        self::assertCount(1, $result);
        self::assertEquals("Budi", $result[0]->getNamaPemesan());
    }

    public function testFindByInstagramUsername() {
        $this->orderRepository->saveOrder($this->order);

        $result = $this->orderRepository->findByInstagramUsername("@johndoe");
        self::assertCount(1, $result);
        self::assertEquals("@johndoe", $result[0]->getInstagramUserNamePemesan());
    }
}
