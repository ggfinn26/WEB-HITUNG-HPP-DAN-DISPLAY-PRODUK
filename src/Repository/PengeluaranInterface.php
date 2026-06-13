<?php
namespace App{
    use App\Pengeluaran;

    interface PengeluaranInterface {
        public function save(Pengeluaran $p): Pengeluaran;
        public function update(Pengeluaran $p): Pengeluaran;
        public function delete(int $id): bool;
        public function findById(int $id): ?Pengeluaran;
        public function findAll(): array;
        public function findByMonthYear(int $month, int $year): array;
        public function getDistinctMonthYears(): array;
    }
}
