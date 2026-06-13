<?php
namespace App;

use App\ProductServiceInterface;
use App\RincianHppServiceInterface;
use App\ValidationException;

class ProductController {
    private ProductServiceInterface $productService;
    private RincianHppServiceInterface $hppService;

    public function __construct(ProductServiceInterface $productService, RincianHppServiceInterface $hppService) {
        $this->productService = $productService;
        $this->hppService = $hppService;
    }

    public function index(): void {
        $products = $this->productService->findAll();
        $title = "Manajemen Produk";
        
        ob_start();
        require __DIR__ . '/../Views/Product/index.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../Views/layout.php';
    }

    public function create(): void {
        $hppList = $this->hppService->findAll();
        $selectedHppId = isset($_GET['hpp_id']) ? (int)$_GET['hpp_id'] : 0;
        $title = "Tambah Produk Baru";

        ob_start();
        require __DIR__ . '/../Views/Product/create.php';
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    public function store(array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid. Silakan coba lagi.";
            header("Location: ?page=products&action=create");
            exit;
        }
        try {
            // Check if city and province are provided
            if (!empty($data['city']) && !empty($data['province'])) {
                $locationQuery = $data['city'] . ', ' . $data['province'] . ', Indonesia';
                $coords = $this->productService->geocodeCity($locationQuery);
                
                if ($coords) {
                    $data['latitude'] = $coords['latitude'];
                    $data['longitude'] = $coords['longitude'];
                }
            }

            // Handle Image Upload
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024; // 2MB
                if ($_FILES['image_file']['size'] > $maxSize) {
                    throw new \Exception("Ukuran file maksimal 2MB.");
                }

                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Validate file type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);

                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
                if (!in_array($mimeType, $allowed, true)) {
                    throw new \Exception("File yang diunggah harus berupa gambar (jpg, png, gif, webp).");
                }
                $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp', 'image/avif' => 'avif'];
                $ext = $extMap[$mimeType];
                $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                    $data['image_url'] = 'uploads/products/' . $fileName;
                }
            }

            $this->productService->save($data);
            $_SESSION['success_message'] = "Produk berhasil ditambahkan!";
            header("Location: ?page=products");
            exit;
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=products&action=create");
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal menyimpan produk. Silakan coba lagi.";
            header("Location: ?page=products&action=create");
            exit;
        }
    }

    public function edit(int $id): void {
        $product = $this->productService->findById($id);
        if (!$product) {
            header("Location: ?page=products");
            exit;
        }

        $title = "Edit Produk";
        
        ob_start();
        require __DIR__ . '/../Views/Product/edit.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../Views/layout.php';
    }

    public function update(int $id, array $data): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid. Silakan coba lagi.";
            header("Location: ?page=products&action=edit&id=" . $id);
            exit;
        }
        try {
            // Check if city and province are provided
            if (!empty($data['city']) && !empty($data['province'])) {
                $locationQuery = $data['city'] . ', ' . $data['province'] . ', Indonesia';
                $coords = $this->productService->geocodeCity($locationQuery);
                
                if ($coords) {
                    $data['latitude'] = $coords['latitude'];
                    $data['longitude'] = $coords['longitude'];
                }
            }

            // Handle Image Upload
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024; // 2MB
                if ($_FILES['image_file']['size'] > $maxSize) {
                    throw new \Exception("Ukuran file maksimal 2MB.");
                }

                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Validate file type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);

                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
                if (!in_array($mimeType, $allowed, true)) {
                    throw new \Exception("File yang diunggah harus berupa gambar (jpg, png, gif, webp).");
                }
                $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp', 'image/avif' => 'avif'];
                $ext = $extMap[$mimeType];
                $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                    $data['image_url'] = 'uploads/products/' . $fileName;
                }
            }

            $this->productService->update($id, $data);
            $_SESSION['success_message'] = "Produk berhasil diupdate!";
            header("Location: ?page=products");
            exit;
        } catch (ValidationException $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ?page=products&action=edit&id=" . $id);
            exit;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Gagal mengupdate produk. Silakan coba lagi.";
            header("Location: ?page=products&action=edit&id=" . $id);
            exit;
        }
    }

    public function delete(int $id, array $data = []): void {
        if (!csrf_verify($data)) {
            $_SESSION['error_message'] = "Permintaan tidak valid.";
            header("Location: ?page=products");
            exit;
        }
        try {
            $this->productService->delete($id);
            $_SESSION['success_message'] = "Produk berhasil dihapus!";
        } catch (\Exception $e) {
            $_SESSION['error_message'] = "Gagal menghapus produk.";
            error_log($e->getMessage());
        }
        header("Location: ?page=products");
        exit;
    }
}
