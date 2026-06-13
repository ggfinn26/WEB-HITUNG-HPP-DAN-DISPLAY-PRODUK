<?php

namespace App{

    interface ProductServiceInterface{
        public function validateProductName(string $name): string;
        public function validateProductPrice(string $price): string;
        public function importProductPriceFromHpp(int $productId): void;
        public function findAll(): array;
        public function findById(int $id): ?\App\Product;
        public function save(array $data): \App\Product;
        public function update(int $id, array $data): \App\Product;
        public function delete(int $id): bool;
        public function geocodeCity(string $location): ?array;
    }
}