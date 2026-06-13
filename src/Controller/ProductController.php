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
        $perPage = 10;
        $currentPage = max(1, (int)($_GET['p'] ?? 1));
        $totalProducts = $this->productService->countAll();
        $totalPages = (int)ceil($totalProducts / $perPage);
        $currentPage = min($currentPage, max(1, $totalPages));

        $products = $this->productService->findPaginated($currentPage, $perPage);
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
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
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
            $uploadDir = __DIR__ . '/../../public/uploads/products/';
            if (isset($_FILES['image_file'])) {
                $fileName = $this->validateAndHandleImageUpload($_FILES['image_file'], $uploadDir);
                if ($fileName !== null) {
                    $data['image_url'] = 'uploads/products/' . $fileName;
                }
            }

            $savedProduct = $this->productService->save($data);
            
            // Process variants
            $this->processVariantsData($savedProduct->getId(), $data, $_FILES);

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
        
        $variantGroups = [];
        $variantOptions = [];
        $variants = [];
        $variantImages = [];
        
        $vr = $this->productService->getVariantRepository();
        if ($vr) {
            $variantGroups = $vr->getGroupsByProduct($id);
            $groupIds = array_map(fn($g) => $g->getId(), $variantGroups);
            $variantOptions = $groupIds ? $vr->getOptionsByGroupIds($groupIds) : [];
            $variants = $vr->getVariantsByProduct($id);
            $variantImages = $vr->getImagesByProduct($id);
        }
        
        ob_start();
        require __DIR__ . '/../Views/Product/edit.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../Views/layout.php';
    }

    public function update(int $id, array $data): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
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
            $uploadDir = __DIR__ . '/../../public/uploads/products/';
            if (isset($_FILES['image_file'])) {
                $fileName = $this->validateAndHandleImageUpload($_FILES['image_file'], $uploadDir);
                if ($fileName !== null) {
                    $data['image_url'] = 'uploads/products/' . $fileName;
                }
            }

            $this->productService->update($id, $data);

            // Process variants
            $this->processVariantsData($id, $data, $_FILES);

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

    private function validateAndHandleImageUpload(array $fileEntry, string $uploadDir): ?string {
        if (!isset($fileEntry) || $fileEntry['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $maxSize = 5 * 1024 * 1024;
        if ($fileEntry['size'] > $maxSize) {
            throw new \Exception("Ukuran file maksimal 5MB.");
        }
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileEntry['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
        if (!in_array($mimeType, $allowed, true)) {
            throw new \Exception("File yang diunggah harus berupa gambar (jpg, png, gif, webp).");
        }
        return $this->processUploadedImage($fileEntry['tmp_name'], $mimeType, $uploadDir);
    }

    private function processUploadedImage(string $tmpPath, string $mimeType, string $uploadDir): string {
        $src = imagecreatefromstring(file_get_contents($tmpPath));
        if (!$src) throw new \Exception("Gagal membaca file gambar.");

        $origW = imagesx($src);
        $origH = imagesy($src);
        $maxDim = 500;

        if ($origW > $maxDim || $origH > $maxDim) {
            $ratio = min($maxDim / $origW, $maxDim / $origH);
            $newW  = (int)round($origW * $ratio);
            $newH  = (int)round($origH * $ratio);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        $fileName   = bin2hex(random_bytes(16)) . '.webp';
        $targetPath = $uploadDir . $fileName;
        imagewebp($dst, $targetPath, 85);
        imagedestroy($dst);

        return $fileName;
    }

    private function processVariantsData(int $productId, array $data, array $files): void {
        $groups = $data['variant_groups'] ?? [];
        $options = $data['variant_options'] ?? [];
        $variants = $data['variants'] ?? [];
        
        // Ensure options are arrays
        foreach ($options as $groupName => $opts) {
            if (is_string($opts)) {
                $options[$groupName] = array_map('trim', explode(',', $opts));
            }
        }

        $images = [];
        $uploadDir = __DIR__ . '/../../public/uploads/products/';

        // Handle uploaded variant images
        if (isset($files['variant_images']) && is_array($files['variant_images']['name'])) {
            foreach ($files['variant_images']['name'] as $index => $name) {
                $entry = [
                    'error'    => $files['variant_images']['error'][$index],
                    'size'     => $files['variant_images']['size'][$index],
                    'tmp_name' => $files['variant_images']['tmp_name'][$index],
                ];
                try {
                    $fileName = $this->validateAndHandleImageUpload($entry, $uploadDir);
                    if ($fileName !== null) {
                        $images[$index] = [
                            'url'        => 'uploads/products/' . $fileName,
                            'is_primary' => false,
                        ];
                    }
                } catch (\Exception $e) {
                    // Skip invalid variant images without failing the whole save
                    error_log("Variant image [$index] skipped: " . $e->getMessage());
                }
            }
        }
        
        // Also keep existing images if editing
        if (isset($data['existing_variant_images']) && is_array($data['existing_variant_images'])) {
            foreach ($data['existing_variant_images'] as $index => $url) {
                if (!isset($images[$index]) && !empty($url)) {
                    $images[$index] = [
                        'url' => $url,
                        'is_primary' => false
                    ];
                }
            }
        }

        // Add variants
        $formattedVariants = [];
        foreach ($variants as $v) {
            if (empty($v['name'])) continue;
            
            $formattedVariants[] = [
                'name' => $v['name'],
                'options' => isset($v['options']) ? explode(',', $v['options']) : [],
                'sku' => $v['sku'] ?? null,
                'price' => isset($v['price']) && $v['price'] !== '' ? $v['price'] : null,
                'stock' => isset($v['stock']) ? (int)$v['stock'] : 0,
                'image_index' => isset($v['image_index']) && $v['image_index'] !== '' ? (int)$v['image_index'] : null,
            ];
        }

        if (!empty($groups) || !empty($formattedVariants)) {
            $this->productService->saveVariants($productId, $groups, $options, $formattedVariants, $images);
        }
    }

    public function delete(int $id, array $data = []): void {
        if (!\App\Helper\CsrfHelper::verifyToken($data)) {
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
