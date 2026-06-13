<?php

namespace App{

    use App\Order;
    use App\OrderServiceInterface;
    use App\OrderInterface;
    use App\ValidationException;
    class OrderService implements OrderServiceInterface{
        
        private OrderInterface $orderRepository;

        public function __construct(OrderInterface $orderRepository){
            $this->orderRepository = $orderRepository;
        }

        public function generateOrderNumber(): string{
            $existingOrders = $this->orderRepository->findAll();
            $existingOrderNumbers = [];
            foreach($existingOrders as $o){
                $existingOrderNumbers[] = $o->getOrderNumber();
            }

            while(true) {
                $timestamp = date('Ymd');
                $random = strtoupper(bin2hex(random_bytes(4)));
                $orderNumber = "ORD-" . $timestamp . "-" . $random;

                if(!in_array($orderNumber, $existingOrderNumbers)){
                    return $orderNumber;
                }
            }
        }

        public function saveOrder(Order $order): Order{
            // Set nomor seri baru untuk order yang dijamin unik
            $order->setOrderNumber($this->generateOrderNumber());
            
            // Simpan ke repository menggunakan metode dari Interface
            return $this->orderRepository->saveOrder($order);
        }
        
        public function updateOrder(Order $order): Order{
            return $this->orderRepository->updateOrder($order);
        }
        public function findAll(): array{
            return $this->orderRepository->findAll();
        }

        public function countAll(): int{
            return $this->orderRepository->countAll();
        }

        public function findPaginated(int $page, int $perPage): array{
            $offset = ($page - 1) * $perPage;
            return $this->orderRepository->findPaginated($perPage, $offset);
        }

        public function findByOrderNumber(string $orderNumber): ?Order{
            return $this->orderRepository->findByOrderNumber($orderNumber);
        }

        public function findByNamaPemesan(string $namaPemesan): array{
            return $this->orderRepository->findByNamaPemesan($namaPemesan);
        }
        public function findByOrderStatus(string $orderStatus): array{
            return $this->orderRepository->findByOrderStatus($orderStatus);
        }
        public function findByInstagramUsername(string $instagramUserNamePemesan): array{
            return $this->orderRepository->findByInstagramUsername($instagramUserNamePemesan);
        }
    }
}