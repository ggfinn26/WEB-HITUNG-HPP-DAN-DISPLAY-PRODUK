<?php

namespace App{

    use App\RincianHpp;

    interface RincianHppServiceInterface{
        public function validateHppName(string $name): string;
        public function validateProductItemList(array $itemList): array;
        public function lexerHpp(array $itemList): array;
        public function parserHpp(array $tokens): array;
        public function calculateItem(array $parserArray): array;
        public function calculateHpp(array $parserArray, int $jumlahProduksi, int $marginKeuntungan): array;
        public function create(RincianHpp $rincianHpp): RincianHpp;
        public function update(RincianHpp $rincianHpp): RincianHpp;
        public function updateHargaJualProduk(int $id, int $hargaJualProduk): RincianHpp;
        public function updateMarginKeuntungan(int $id, int $marginKeuntungan): RincianHpp;
        public function updateJumlahProduksi(int $id, int $jumlahProduksi): RincianHpp;
        public function updateItemProduksi(int $id, array $productItemList): RincianHpp;
        public function delete(int $id): bool;
        public function findById(int $id): ?RincianHpp;
        public function findByName(string $name): ?RincianHpp;
        public function findAll(): array;
        public function count(): int;
        public function search(string $query): array;
    }
}