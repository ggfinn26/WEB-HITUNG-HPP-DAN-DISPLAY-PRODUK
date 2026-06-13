<?php

namespace App{

    interface ProductServiceInterface{
        public function validateProductName(string $name): string;
        public function validateProductPrice(string $price): string;
        public function importProductPriceFromHpp(int $productId): void;
        public function findAll(): array;
        public function countAll(): int;
        public function findPaginated(int $page, int $perPage): array;
        public function findById(int $id): ?\App\Product;
        public function save(array $data): \App\Product;
        public function update(int $id, array $data): \App\Product;
        public function delete(int $id): bool;
        public function saveVariants(int $productId, array $groups, array $options, array $variants, array $images): void;
        public function geocodeCity(string $location): ?array;
    }
}