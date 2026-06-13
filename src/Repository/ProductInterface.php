<?php

namespace App{
    require_once __DIR__."/../Entity/Product.php";
    use App\Product;

    interface ProductInterface{
        public function save(Product $product): Product;
        public function update(Product $product): Product;
        public function delete(int $id): bool;
        public function findById(int $id): ?Product;
        public function findByName(string $name): ?Product;
        public function findAll(): array;
        public function findAllSortedByPriceAsc(string $direction = 'ASC'): array;
        public function findAllSortedByPriceDesc(string $direction = 'DESC'): array;
        public function updatePrice(int $id, string $price): void;
        public function findByHppId(int $hppId): ?Product;
    }
}