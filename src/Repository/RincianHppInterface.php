<?php

namespace App{

    use App\RincianHpp;
    interface RincianHppInterface{
        public function create(RincianHpp $rincianHpp): RincianHpp;
        public function update(RincianHpp $rincianHpp): RincianHpp;
        public function updateHargaJualProduk(int $id, string $hargaJualProduk): RincianHpp;
        public function updateMarginKeuntungan(int $id, int $marginKeuntungan): RincianHpp;
        public function updateJumlahProduksi(int $id, int $jumlahProduksi): RincianHpp;
        public function updateItemProduksi(int $id, string $productItemList): RincianHpp;
        public function delete(int $id): bool;
        public function findById(int $id): ?RincianHpp;
        public function findByName(string $name): ?RincianHpp;
        public function findAll(): array;
        public function count(): int;
        public function search(string $query): array;
        public function findAllForSesi(): array;
    }
}