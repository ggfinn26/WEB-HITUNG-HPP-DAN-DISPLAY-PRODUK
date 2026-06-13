<?php
namespace App;

use App\LaporanService;
use App\ValidationException;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController {
    private LaporanService $laporanService;

    public function __construct(LaporanService $laporanService) {
        $this->laporanService = $laporanService;
    }

    public function index(): void {
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

        $bulan = max(1, min(12, $bulan));
        $tahun = max(2000, min((int)date('Y') + 1, $tahun));

        $report        = $this->laporanService->getMonthlyReport($bulan, $tahun);
        $availableMonths = $this->laporanService->getAvailableMonths();
        $title = "Laporan Bulanan";

        ob_start();
        require __DIR__ . '/../Views/Laporan/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function pendapatan(): void {
        $orders = $this->laporanService->getAllOrders();
        $title = "Manajemen Pendapatan";

        ob_start();
        require __DIR__ . '/../Views/Laporan/pendapatan.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function deleteOrder(int $id, array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=laporan&action=pendapatan");
            exit;
        }
        try {
            $this->laporanService->deleteOrder($id);
            $_SESSION['success_message'] = "Order berhasil dihapus.";
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menghapus order.";
        }
        header("Location: ?page=laporan&action=pendapatan");
        exit;
    }

    public function bulkDeleteOrders(array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=laporan&action=pendapatan");
            exit;
        }
        $ids = $data['order_ids'] ?? [];
        if (empty($ids)) {
            $_SESSION['error_message'] = "Pilih minimal satu order untuk dihapus.";
            header("Location: ?page=laporan&action=pendapatan");
            exit;
        }
        try {
            $count = $this->laporanService->bulkDeleteOrders($ids);
            $_SESSION['success_message'] = "$count order berhasil dihapus.";
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menghapus order.";
        }
        header("Location: ?page=laporan&action=pendapatan");
        exit;
    }

    public function storePengeluaran(array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: " . $this->redirectBack($data));
            exit;
        }
        try {
            $this->laporanService->savePengeluaran($data);
            $_SESSION['success_message'] = "Pengeluaran berhasil ditambahkan.";
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menyimpan pengeluaran.";
        }
        $bulan = $data['redirect_bulan'] ?? date('n');
        $tahun = $data['redirect_tahun'] ?? date('Y');
        header("Location: ?page=laporan&bulan=$bulan&tahun=$tahun");
        exit;
    }

    public function editPengeluaran(int $id): void {
        $pengeluaran = $this->laporanService->findPengeluaranById($id);
        if (!$pengeluaran) {
            header("Location: ?page=laporan");
            exit;
        }
        $title = "Edit Pengeluaran";
        ob_start();
        require __DIR__ . '/../Views/Laporan/edit_pengeluaran.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function updatePengeluaran(int $id, array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=laporan&action=editPengeluaran&id=$id");
            exit;
        }
        try {
            $this->laporanService->updatePengeluaran($id, $data);
            $_SESSION['success_message'] = "Pengeluaran berhasil diupdate.";
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=laporan&action=editPengeluaran&id=$id");
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal mengupdate pengeluaran.";
            header("Location: ?page=laporan&action=editPengeluaran&id=$id");
            exit;
        }
        header("Location: ?page=laporan");
        exit;
    }

    public function deletePengeluaran(int $id, array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=laporan");
            exit;
        }
        try {
            $this->laporanService->deletePengeluaran($id);
            $_SESSION['success_message'] = "Pengeluaran berhasil dihapus.";
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menghapus pengeluaran.";
        }
        $bulan = isset($data['redirect_bulan']) ? (int)$data['redirect_bulan'] : (int)date('n');
        $tahun = isset($data['redirect_tahun']) ? (int)$data['redirect_tahun'] : (int)date('Y');
        $bulan = max(1, min(12, $bulan));
        $tahun = max(2000, min((int)date('Y') + 1, $tahun));
        header("Location: ?page=laporan&bulan=$bulan&tahun=$tahun");
        exit;
    }

    public function exportPdf(): void {
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
        $bulan = max(1, min(12, $bulan));
        $tahun = max(2000, min((int)date('Y') + 1, $tahun));

        $report = $this->laporanService->getMonthlyReport($bulan, $tahun);

        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $currentMonthLabel = $namaBulan[$bulan] . ' ' . $tahun;

        ob_start();
        require __DIR__ . '/../Views/Laporan/pdf.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_' . $namaBulan[$bulan] . '_' . $tahun . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    private function redirectBack(array $data): string {
        $b = $data['redirect_bulan'] ?? date('n');
        $t = $data['redirect_tahun'] ?? date('Y');
        return "?page=laporan&bulan=$b&tahun=$t";
    }
}
