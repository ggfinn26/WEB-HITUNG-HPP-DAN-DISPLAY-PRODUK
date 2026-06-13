<?php

namespace App\Test{

    require_once __DIR__ . '/../Service/OrderServiceInterface.php';
    require_once __DIR__ . '/../Service/OrderService.php';
    require_once __DIR__ . '/../Repository/OrderInterface.php';
    require_once __DIR__ . '/../Entity/Order.php';

    use PHPUnit\Framework\TestCase;
    use App\OrderService;
    use App\OrderInterface;
    use App\Order;

    class OrderServiceTest extends TestCase {
        private OrderInterface $orderRepositoryMock;
        private OrderService $orderService;

        protected function setUp(): void {
            parent::setUp();
            $this->orderRepositoryMock = $this->createMock(OrderInterface::class);
            $this->orderService = new OrderService($this->orderRepositoryMock);
        }

        public function testGenerateOrderNumberUnique() {
            // Skenario: Repository mengembalikan order dengan nomor ORD-1234
            $existingOrder = $this->createMock(Order::class);
            $existingOrder->method('getOrderNumber')->willReturn('ORD-1234');
            
            $this->orderRepositoryMock->method('findAll')->willReturn([$existingOrder]);

            $newOrderNumber = $this->orderService->generateOrderNumber();

            // Memastikan nomor baru tidak sama dengan ORD-1234
            $this->assertNotEquals('ORD-1234', $newOrderNumber);
            $this->assertStringStartsWith('ORD-', $newOrderNumber);
        }

        public function testSaveOrder() {
            $order = new Order(
                0, 
                "", 
                "[]", 
                "0", 
                "Pending", 
                "John", 
                "Alamat", 
                "081", 
                "john_ig", 
                new \DateTime(), 
                new \DateTime()
            );

            // Saat findAll dipanggil untuk mengecek ID unique, kembalikan array kosong
            $this->orderRepositoryMock->method('findAll')->willReturn([]);

            // Memastikan method saveOrder pada repository dipanggil tepat satu kali
            $this->orderRepositoryMock->expects($this->once())
                 ->method('saveOrder')
                 ->with($this->callback(function($savedOrder) {
                     // Harus mempunyai nomor order yang baru di-generate
                     return strpos($savedOrder->getOrderNumber(), 'ORD-') === 0;
                 }))
                 ->willReturn($order);

            $result = $this->orderService->saveOrder($order);
            
            $this->assertInstanceOf(Order::class, $result);
            $this->assertNotEmpty($result->getOrderNumber());
        }

        public function testUpdateOrder() {
            $order = $this->createMock(Order::class);

            $this->orderRepositoryMock->expects($this->once())
                 ->method('updateOrder')
                 ->with($order)
                 ->willReturn($order);

            $result = $this->orderService->updateOrder($order);
            $this->assertSame($order, $result);
        }
    }
}
