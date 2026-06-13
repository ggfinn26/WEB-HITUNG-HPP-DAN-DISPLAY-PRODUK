<?php
namespace App;

use App\RincianHppServiceInterface;
use App\RincianHpp;
use App\ValidationException;

class RincianHppController {
    private RincianHppServiceInterface $hppService;

    public function __construct(RincianHppServiceInterface $hppService) {
        $this->hppService = $hppService;
    }

    public function index(): void {
        $hppList = $this->hppService->findAll();
        $title = "Manajemen HPP";
        ob_start();
        require __DIR__ . '/../Views/Hpp/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function create(): void {
        $satuanOptions = RincianHppService::SATUAN_OPTION;
        $title = "Buat Perhitungan HPP";
        ob_start();
        require __DIR__ . '/../Views/Hpp/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function store(array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=hpp&action=create");
            exit;
        }

        try {
            $name = $this->hppService->validateHppName($data['name'] ?? '');
            $jumlahProduksi = max(1, (int)($data['jumlah_produksi'] ?? 1));
            $marginKeuntungan = max(0, (int)($data['margin_keuntungan'] ?? 0));

            $itemList = json_decode($data['product_item_list'] ?? '[]', true);
            if (!is_array($itemList) || empty($itemList)) {
                throw new ValidationException("Minimal satu bahan harus diisi.");
            }

            $this->hppService->validateProductItemList($itemList);

            $tokens    = $this->hppService->lexerHpp($itemList);
            $parsed    = $this->hppService->parserHpp($tokens);
            $calculated = $this->hppService->calculateItem($parsed);
            $result    = $this->hppService->calculateHpp($calculated, $jumlahProduksi, $marginKeuntungan);

            $hpp = new RincianHpp(
                0, 0, $name, $marginKeuntungan,
                json_encode($itemList),
                $jumlahProduksi,
                (string)round($result['total_biaya_bahan'], 2),
                (string)round($result['hpp_produksi_final'], 2),
                (string)round($result['harga_jual_final'], 2),
                new \DateTime(), new \DateTime(), false
            );

            $this->hppService->create($hpp);
            $_SESSION['success_message'] = "HPP berhasil disimpan! Sekarang bisa membuat produk.";
            header("Location: ?page=hpp");
            exit;
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=hpp&action=create");
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menyimpan HPP.";
            header("Location: ?page=hpp&action=create");
            exit;
        }
    }

    public function edit(int $id): void {
        $hpp = $this->hppService->findById($id);
        if (!$hpp) {
            $_SESSION['error_message'] = "HPP tidak ditemukan.";
            header("Location: ?page=hpp");
            exit;
        }
        $satuanOptions  = RincianHppService::SATUAN_OPTION;
        $existingItems  = json_decode($hpp->getProductItemList(), true) ?? [];
        $title = "Edit HPP: " . $hpp->getName();
        ob_start();
        require __DIR__ . '/../Views/Hpp/edit.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function updateHpp(int $id, array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=hpp&action=edit&id=$id");
            exit;
        }
        try {
            $hpp = $this->hppService->updateFull($id, $data);
            $linked = $hpp->getProductId() > 0 ? ' Harga produk terkait ikut diperbarui.' : '';
            $_SESSION['success_message'] = "HPP berhasil diperbarui.$linked";
            header("Location: ?page=hpp");
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=hpp&action=edit&id=$id");
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal memperbarui HPP.";
            header("Location: ?page=hpp&action=edit&id=$id");
        }
        exit;
    }

    public function delete(int $id, array $data = []): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=hpp");
            exit;
        }
        try {
            $this->hppService->delete($id);
            $_SESSION['success_message'] = "HPP berhasil dihapus.";
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menghapus HPP.";
        }
        header("Location: ?page=hpp");
        exit;
    }
}
