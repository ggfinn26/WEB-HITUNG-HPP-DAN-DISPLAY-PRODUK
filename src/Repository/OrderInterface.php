<?php

namespace App{
    use App\Order;

    interface OrderInterface{

        public function saveOrder(Order $order): Order;
        public function updateOrder(Order $order): Order;
        public function findAll(): array;
        public function countAll(): int;
        public function findPaginated(int $limit, int $offset): array;
        public function findByOrderNumber(string $orderNumber): ?Order;
        public function findByNamaPemesan(string $namaPemesan): array;
        public function findByOrderStatus(string $orderStatus): array;
        public function findByInstagramUsername(string $instagramUserNamePemesan): array;
        public function findByMonthYear(int $month, int $year): array;
        public function deleteById(int $id): bool;
        public function deleteByIds(array $ids): int;
    }
}