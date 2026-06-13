<?php

namespace App{

    use App\Order;

    interface OrderServiceInterface{
        public function generateOrderNumber(): string;
        public function saveOrder(Order $order): Order;
        public function updateOrder(Order $order): Order;
        public function findAll(): array;
        public function countAll(): int;
        public function findPaginated(int $page, int $perPage): array;
        public function findByOrderNumber(string $orderNumber): ?Order;
        public function findByNamaPemesan(string $namaPemesan): array;
        public function findByOrderStatus(string $orderStatus): array;
        public function findByInstagramUsername(string $instagramUserNamePemesan): array;
    }
}