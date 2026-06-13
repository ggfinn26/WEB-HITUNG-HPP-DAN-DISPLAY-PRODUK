<?php
namespace App{
    require_once __DIR__ . '/../Repository/OrderInterface.php';
    require_once __DIR__ . '/../Repository/PengeluaranInterface.php';
    require_once __DIR__ . '/../Exception/ValidationException.php';

    use App\OrderInterface;
    use App\PengeluaranInterface;
    use App\Pengeluaran;
    use App\ValidationException;

    class LaporanService {
        private OrderInterface $orderRepository;
        private PengeluaranInterface $pengeluaranRepository;

        public function __construct(OrderInterface $orderRepository, PengeluaranInterface $pengeluaranRepository) {
            $this->orderRepository      = $orderRepository;
            $this->pengeluaranRepository = $pengeluaranRepository;
        }

        public function getMonthlyReport(int $bulan, int $tahun): array {
            $orders      = $this->orderRepository->findByMonthYear($bulan, $tahun);
            $pengeluaran = $this->pengeluaranRepository->findByMonthYear($bulan, $tahun);

            $totalOmset       = array_reduce($orders, fn($carry, $o) => $carry + (float)$o->getSubTotal(), 0.0);
            $totalPendapatan  = array_reduce($orders, fn($carry, $o) => $carry + $this->calculateOrderMargin($o), 0.0);
            $totalPengeluaran = array_reduce($pengeluaran, fn($carry, $p) => $carry + (float)$p->getJumlah(), 0.0);

            return [
                'orders'            => $orders,
                'pengeluaran'       => $pengeluaran,
                'total_omset'       => $totalOmset,
                'total_pendapatan'  => $totalPendapatan,
                'total_pengeluaran' => $totalPengeluaran,
                'keuntungan_bersih' => $totalPendapatan - $totalPengeluaran,
            ];
        }

        private function calculateOrderMargin(\App\Order $order): float {
            $items = json_decode($order->getListItemOrder(), true) ?? [];
            return (float)array_reduce($items, function($carry, $item) {
                $price = (float)($item['price'] ?? 0);
                $modal = (float)($item['modal'] ?? 0);
                $qty   = (int)($item['qty'] ?? 1);
                return $carry + (($price - $modal) * $qty);
            }, 0.0);
        }

        public function getAvailableMonths(): array {
            $months = [];
            foreach ($this->orderRepository->getDistinctMonthYears() as $row) {
                $key = sprintf('%04d-%02d', $row['year'], $row['month']);
                $months[$key] = ['year' => (int)$row['year'], 'month' => (int)$row['month']];
            }
            foreach ($this->pengeluaranRepository->getDistinctMonthYears() as $row) {
                $key = sprintf('%04d-%02d', $row['year'], $row['month']);
                $months[$key] = ['year' => (int)$row['year'], 'month' => (int)$row['month']];
            }
            krsort($months);
            return array_values($months);
        }

        public function getAllOrders(): array {
            return $this->orderRepository->findAll();
        }

        public function deleteOrder(int $id): bool {
            return $this->orderRepository->deleteById($id);
        }

        public function bulkDeleteOrders(array $ids): int {
            $safeIds = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
            if (empty($safeIds)) return 0;
            return $this->orderRepository->deleteByIds(array_values($safeIds));
        }

        public function savePengeluaran(array $data): Pengeluaran {
            $keterangan = trim($data['keterangan'] ?? '');
            if ($keterangan === '') throw new ValidationException("Keterangan tidak boleh kosong.");

            $jumlah = $data['jumlah'] ?? '';
            if (!is_numeric($jumlah) || (float)$jumlah <= 0) throw new ValidationException("Jumlah harus berupa angka positif.");

            $tanggalStr = $data['tanggal'] ?? '';
            try {
                $tanggal = new \DateTime($tanggalStr);
            } catch (\Exception $e) {
                throw new ValidationException("Format tanggal tidak valid.");
            }

            $p = new Pengeluaran(0, $tanggal, $keterangan, (string)(float)$jumlah, new \DateTime(), new \DateTime());
            return $this->pengeluaranRepository->save($p);
        }

        public function updatePengeluaran(int $id, array $data): Pengeluaran {
            $p = $this->pengeluaranRepository->findById($id);
            if (!$p) throw new ValidationException("Data pengeluaran tidak ditemukan.");

            $keterangan = trim($data['keterangan'] ?? '');
            if ($keterangan === '') throw new ValidationException("Keterangan tidak boleh kosong.");

            $jumlah = $data['jumlah'] ?? '';
            if (!is_numeric($jumlah) || (float)$jumlah <= 0) throw new ValidationException("Jumlah harus berupa angka positif.");

            $tanggalStr = $data['tanggal'] ?? '';
            try {
                $tanggal = new \DateTime($tanggalStr);
            } catch (\Exception $e) {
                throw new ValidationException("Format tanggal tidak valid.");
            }

            $p->setKeterangan($keterangan);
            $p->setJumlah((string)(float)$jumlah);
            $p->setTanggal($tanggal);
            return $this->pengeluaranRepository->update($p);
        }

        public function deletePengeluaran(int $id): bool {
            $p = $this->pengeluaranRepository->findById($id);
            if (!$p) throw new ValidationException("Data pengeluaran tidak ditemukan.");
            return $this->pengeluaranRepository->delete($id);
        }

        public function findPengeluaranById(int $id): ?Pengeluaran {
            return $this->pengeluaranRepository->findById($id);
        }
    }
}
