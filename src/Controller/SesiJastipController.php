<?php
namespace App;

use App\SesiJastipService;
use App\ValidationException;

class SesiJastipController {
    private SesiJastipService       $service;
    private RincianHppRepositoryImpl $hppRepo;
    private PengeluaranRepositoryImpl $pengeluaranRepo;

    public function __construct(
        SesiJastipService $service,
        RincianHppRepositoryImpl $hppRepo,
        PengeluaranRepositoryImpl $pengeluaranRepo
    ) {
        $this->service         = $service;
        $this->hppRepo         = $hppRepo;
        $this->pengeluaranRepo = $pengeluaranRepo;
    }

    public function index(): void {
        $sesiList     = $this->service->findAll();
        $sesiProdukMap = [];
        foreach ($sesiList as $s) {
            if ($s->getStatus() === 'draft') {
                $sesiProdukMap[$s->getId()] = $this->service->findProdukBySesiId($s->getId());
            }
        }
        $title = 'Analisis Sesi Trip';

        ob_start();
        require __DIR__ . '/../Views/Sesi/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function create(): void {
        $hppForSesi = $this->hppRepo->findAllForSesi();
        $title = 'Buat Sesi Trip Baru';

        ob_start();
        require __DIR__ . '/../Views/Sesi/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function store(array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=sesi&action=create");
            exit;
        }
        try {
            $produkRows = json_decode($data['produk_json'] ?? '[]', true);
            if (!is_array($produkRows)) $produkRows = [];
            $this->service->simpanSesi($data, $produkRows);
            $_SESSION['success_message'] = "Sesi berhasil disimpan.";
            header("Location: ?page=sesi");
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=sesi&action=create");
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Terjadi kesalahan. Silakan coba lagi.";
            header("Location: ?page=sesi&action=create");
        }
        exit;
    }

    public function tutup(int $id, array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=sesi");
            exit;
        }
        try {
            $aktualQtys = $data['aktual_qty'] ?? [];
            if (!is_array($aktualQtys)) $aktualQtys = [];
            $this->service->tutupSesi($id, $aktualQtys, $this->pengeluaranRepo);
            $_SESSION['success_message'] = "Sesi ditutup. Biaya tetap tercatat ke pengeluaran.";
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menutup sesi.";
        }
        header("Location: ?page=sesi");
        exit;
    }

    public function hapus(int $id, array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=sesi");
            exit;
        }
        try {
            $this->service->hapusSesi($id);
            $_SESSION['success_message'] = "Sesi berhasil dihapus.";
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
        header("Location: ?page=sesi");
        exit;
    }

    public function detail(int $id): void {
        $sesi = $this->service->findById($id);
        if (!$sesi) {
            $_SESSION['error_message'] = "Sesi tidak ditemukan.";
            header("Location: ?page=sesi");
            exit;
        }
        $komponen   = $this->service->findKomponenBySesiId($id);
        $produkList = $this->service->findProdukBySesiId($id);
        $totalBiaya = array_sum(array_map(fn($k) => $k->getJumlah(), $komponen));

        $kalkulasi  = [];
        $bep        = null;
        $labaAktual = null;

        if (!empty($produkList)) {
            $inputEstimasi = array_map(fn(SesiProduk $p) => [
                'harga_jual' => $p->getHargaJualSnapshot(),
                'hpp'        => $p->getHppPerPcsSnapshot(),
                'margin'     => $p->getMarginSnapshot(),
                'qty'        => $p->getEstimasiQty(),
            ], $produkList);

            $kalkulasi = $this->service->hitungDistribusi(
                $inputEstimasi, $totalBiaya, $sesi->getPersenProporsional()
            );

            $bep = $this->service->hitungBEPMultiProduk($inputEstimasi, $totalBiaya);

            if ($sesi->getStatus() === 'selesai') {
                $aktualResult = $this->service->hitungLabaAktual(
                    $produkList, $totalBiaya, $sesi->getPersenProporsional()
                );
                $labaAktual = $aktualResult;
            }
        }

        $title = 'Detail Sesi: ' . $sesi->getNamaSesi();
        ob_start();
        require __DIR__ . '/../Views/Sesi/detail.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }
}
