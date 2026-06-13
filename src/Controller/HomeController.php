<?php

namespace App;

require_once __DIR__ . '/../Service/ProductServiceInterface.php';
require_once __DIR__ . '/../Service/OrderServiceInterface.php';

use App\ProductServiceInterface;
use App\OrderServiceInterface;

class HomeController {
    private ProductServiceInterface $productService;
    private OrderServiceInterface $orderService;

    public function __construct(ProductServiceInterface $productService, OrderServiceInterface $orderService) {
        $this->productService = $productService;
        $this->orderService = $orderService;
    }

    public function index() {
        $products = $this->productService->findPaginated(1, 6);

        $productsArray = array_map(fn($p) => $p->toArray(), $products);
        $productsJson  = json_encode($productsArray);

        $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $title       = 'Mbu Titip by Arunga Arungi Dunia | Jasa Titip Terpercaya Se-Indonesia';
        $description = 'Jasa titip (jastip) terpercaya dari berbagai penjuru Nusantara. Temukan barang-barang favorit keluarga dari berbagai daerah dengan layanan personal shopper terbaik dan harga terjangkau.';
        $canonical   = $baseUrl . '/';
        $ogImage     = $baseUrl . '/logo.webp';
        $structuredData = json_encode([
            '@context'    => 'https://schema.org',
            '@type'       => 'LocalBusiness',
            'name'        => 'Mbu Titip by Arunga Arungi Dunia',
            'alternateName' => 'Jastip Arunga',
            'description' => $description,
            'url'         => $baseUrl . '/',
            'logo'        => $baseUrl . '/logo.webp',
            'image'       => $baseUrl . '/logo.webp',
            'telephone'   => '+' . ($_ENV['ADMIN_WA'] ?? '62895380123352'),
            'priceRange'  => '$$',
            'serviceType' => 'Jasa Titip / Personal Shopper',
            'areaServed'  => 'Indonesia',
            'sameAs'      => ['https://www.instagram.com/arungaarungidunia'],
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => '+' . ($_ENV['ADMIN_WA'] ?? '62895380123352'),
                'contactType' => 'customer service',
                'availableLanguage' => 'Indonesian',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        ob_start();
        require __DIR__ . '/../Views/Home/index.php';
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    public function catalog() {
        $perPage = 12;
        $currentPage = max(1, (int)($_GET['p'] ?? 1));
        $totalProducts = $this->productService->countAll();
        $totalPages = max(1, (int)ceil($totalProducts / $perPage));
        $currentPage = min($currentPage, $totalPages);

        $products = $this->productService->findPaginated($currentPage, $perPage);

        $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $title       = 'Katalog Produk Jastip | Mbu Titip Arunga Arungi Dunia';
        $description = 'Jelajahi katalog lengkap barang jastip dari berbagai pelosok nusantara. Produk pilihan tangan pertama dengan kualitas terjamin untuk keluarga tersayang.';
        $canonical   = $baseUrl . '/?page=catalog&p=' . $currentPage;
        $ogImage     = $baseUrl . '/logo.webp';

        // ItemList structured data for catalog
        $itemListElements = [];
        foreach ($products as $i => $p) {
            $itemListElements[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $p->getName(),
                'url'      => $baseUrl . '/?page=catalog',
            ];
        }
        $structuredData = json_encode([
            '@context'        => 'https://schema.org',
            '@type'           => 'ItemList',
            'name'            => 'Katalog Produk Jastip Arunga',
            'description'     => $description,
            'url'             => $canonical,
            'numberOfItems'   => count($products),
            'itemListElement' => $itemListElements,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Fetch variant data using BATCH queries — no N+1
        $variantsData = [];
        $vr = $this->productService->getVariantRepository();
        if ($vr && !empty($products)) {
            $productIds = array_map(fn($p) => $p->getId(), $products);

            // 1 query: all groups for all products
            $groupsByProduct = $vr->getGroupsByProducts($productIds);

            // 1 query: all options for all groups found above
            $allGroupIds = [];
            foreach ($groupsByProduct as $groups) {
                foreach ($groups as $g) {
                    $allGroupIds[] = $g->getId();
                }
            }
            $optionsByGroup = $vr->getOptionsByGroupIds($allGroupIds);

            // 1 query: all variants + 1 more for combinations (inside getVariantsByProducts)
            $variantsByProduct = $vr->getVariantsByProducts($productIds);

            // 1 query: all images
            $imagesByProduct = $vr->getImagesByProducts($productIds);

            // Assemble result map
            foreach ($productIds as $pid) {
                $groups = [];
                foreach ($groupsByProduct[$pid] ?? [] as $g) {
                    $opts = $optionsByGroup[$g->getId()] ?? [];
                    $groups[] = [
                        'name'    => $g->getName(),
                        'options' => array_map(fn($o) => $o->getName(), $opts),
                    ];
                }
                $variantsData[$pid] = [
                    'groups'   => $groups,
                    'variants' => $variantsByProduct[$pid] ?? [],
                    'images'   => $imagesByProduct[$pid] ?? [],
                ];
            }
        }

        ob_start();
        require __DIR__ . '/../Views/Home/catalog.php';
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    public function trackOrder() {
        $orderNumber = $_POST['orderNumber'] ?? '';
        $order = null;
        $error = null;

        if (empty(trim($orderNumber))) {
            $error = "Silakan masukkan Nomor Pesanan (Resi).";
        } else {
            $order = $this->orderService->findByOrderNumber(trim($orderNumber));
            if (!$order) {
                $error = "Pesanan dengan nomor <strong>" . htmlspecialchars($orderNumber) . "</strong> tidak ditemukan.";
            }
        }

        $title = "Lacak Pesanan | Jastip Arunga";
        
        require __DIR__ . '/../Views/Home/track.php';
    }
}
