<?php

namespace App\Test{

    require_once __DIR__ . '/../Controller/OrderController.php';
    require_once __DIR__ . '/../Service/OrderServiceInterface.php';
    require_once __DIR__ . '/../Entity/Order.php';

    use PHPUnit\Framework\TestCase;
    use App\OrderController;
    use App\OrderServiceInterface;
    use App\Order;

    class OrderControllerTest extends TestCase {
        private OrderServiceInterface $orderServiceMock;
        private OrderController $orderController;

        protected function setUp(): void {
            parent::setUp();
            $this->orderServiceMock = $this->createMock(OrderServiceInterface::class);
            $this->orderController = new OrderController($this->orderServiceMock);
        }

        public function testIndexRendersView() {
            $this->orderServiceMock->method('findAll')->willReturn([]);

            ob_start();
            $this->orderController->index();
            $output = ob_get_clean();

            // Memastikan konten spesifik dari layout dan view "index.php" dirender
            $this->assertStringContainsString('Daftar Order', $output);
            $this->assertStringContainsString('Belum ada pesanan masuk', $output); // Karena findAll kembalikan array kosong
            $this->assertStringContainsString('Sistem Manajemen Order', $output); // Dari layout
        }

        public function testCreateRendersView() {
            ob_start();
            $this->orderController->create();
            $output = ob_get_clean();

            $this->assertStringContainsString('Formulir Pesanan', $output);
            $this->assertStringContainsString('Nama Lengkap', $output);
            $this->assertStringContainsString('No. WhatsApp', $output);
        }

        public function testStoreCallsService() {
            $data = [
                'namaPemesan' => 'Budi',
                'whatsappPemesan' => '08123',
                'alamatPemesan' => 'Jl. Jalan'
            ];

            $this->orderServiceMock->expects($this->once())
                 ->method('saveOrder')
                 ->with($this->callback(function($order) use ($data) {
                     return $order->getNamaPemesan() === $data['namaPemesan'] &&
                            $order->getWhatsappPemesan() === $data['whatsappPemesan'];
                 }));

            // Test store dipanggil. suppress output warning header
            @$this->orderController->store($data);
        }

        public function testShowRendersView() {
            $order = new Order(
                1, 
                "ORD-9999", 
                "[]", 
                "100000", 
                "Pending", 
                "Asep", 
                "Alamat Asep", 
                "08555", 
                "asep_ig", 
                new \DateTime(), 
                new \DateTime()
            );

            $this->orderServiceMock->method('findAll')->willReturn([$order]);

            ob_start();
            $this->orderController->show("ORD-9999");
            $output = ob_get_clean();

            $this->assertStringContainsString('Detail Order', $output);
            $this->assertStringContainsString('Asep', $output);
            $this->assertStringContainsString('ORD-9999', $output);
            $this->assertStringContainsString('100.000', $output);
        }

        public function testUpdateCallsService() {
            $order = new Order(
                1, 
                "ORD-9999", 
                "[]", 
                "100000", 
                "Pending", 
                "Asep", 
                "Alamat Asep", 
                "08555", 
                "asep_ig", 
                new \DateTime(), 
                new \DateTime()
            );

            $this->orderServiceMock->method('findAll')->willReturn([$order]);

            $this->orderServiceMock->expects($this->once())
                 ->method('updateOrder')
                 ->with($this->callback(function($updatedOrder) {
                     return $updatedOrder->getOrderStatus() === 'Selesai';
                 }));

            @$this->orderController->update("ORD-9999", ['orderStatus' => 'Selesai']);
        }
    }
}
