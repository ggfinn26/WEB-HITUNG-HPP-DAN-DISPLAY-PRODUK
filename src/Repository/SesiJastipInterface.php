<?php
namespace App;

interface SesiJastipInterface {
    public function save(SesiJastip $sesi): SesiJastip;
    public function update(SesiJastip $sesi): SesiJastip;
    public function findById(int $id): ?SesiJastip;
    public function findAll(): array;
    public function delete(int $id): bool;

    public function saveKomponen(BiayaKomponenSesi $k): BiayaKomponenSesi;
    public function findKomponenBySesiId(int $sesiId): array;
    public function deleteKomponenBySesiId(int $sesiId): void;

    public function saveSesiBobot(SesiBobot $b): SesiBobot;
    public function findSesiBobotBySesiId(int $sesiId): ?SesiBobot;

    public function saveProduk(SesiProduk $p): SesiProduk;
    public function findProdukBySesiId(int $sesiId): array;
    public function updateProdukAktualQty(int $sesiProdukId, int $aktualQty): void;
    public function deleteProdukBySesiId(int $sesiId): void;
}
