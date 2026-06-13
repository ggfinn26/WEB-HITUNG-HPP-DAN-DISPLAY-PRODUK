<?php
namespace App;

use App\Order;
use App\OrderService;
use App\Helper\CsrfHelper;

class CartController {
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function index(): void {
        $success = null;
        $adminWa = $_ENV['ADMIN_WA'] ?? '62895380123352';
        $adminIg = $_ENV['ADMIN_IG'] ?? 'arungaarungidunia';

        $orderNumber = trim($_GET['order'] ?? '');
        if ($orderNumber !== '') {
            $order = $this->orderService->findByOrderNumber($orderNumber);
            if ($order) {
                $items     = json_decode($order->getListItemOrder(), true) ?? [];
                $itemsText = '';
                foreach ($items as $item) {
                    $qty      = (int)($item['qty'] ?? 1);
                    $price    = (float)($item['price'] ?? 0);
                    $name     = $item['name'] ?? '';
                    $subtotal = 'Rp ' . number_format($qty * $price, 0, ',', '.');
                    $itemsText .= "• {$name} x {$qty} — {$subtotal}\n";
                }
                $total  = 'Rp ' . number_format((float)$order->getSubTotal(), 0, ',', '.');
                $nama   = $order->getNamaPemesan();
                $wa     = $order->getWhatsappPemesan() ?: '-';
                $alamat = $order->getAlamatPemesan() ?: '';
                $ig     = $order->getInstagramUserNamePemesan() ?: '';

                $success = [
                    'orderNumber' => $orderNumber,
                    'items'       => $items,
                    'total'       => $total,
                    'nama'        => $nama,
                    'wa'          => $wa,
                    'alamat'      => $alamat,
                    'ig'          => $ig,
                ];
            }
        }

        $title = 'Keranjang Belanja | Mbu Titip';
        ob_start();
        require __DIR__ . '/../Views/Cart/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layout.php';
    }

    public function checkout(array $data): void {
        if (!CsrfHelper::verifyToken($data)) {
            $_SESSION['cart_error'] = "Permintaan tidak valid. Silakan coba lagi.";
            header("Location: ?page=cart");
            exit;
        }

        $listItemOrder = $data['list_item_order'] ?? '[]';
        $items = json_decode($listItemOrder, true);

        if (empty($items) || !is_array($items)) {
            $_SESSION['cart_error'] = "Keranjang kosong. Tambahkan produk terlebih dahulu.";
            header("Location: ?page=cart");
            exit;
        }

        $namaPemesan    = trim($data['namaPemesan'] ?? '');
        $alamatPemesan  = trim($data['alamatPemesan'] ?? '');
        $whatsapp       = trim($data['whatsappPemesan'] ?? '');
        $instagram      = trim($data['instagramUserNamePemesan'] ?? '');
        $subTotal       = $data['subTotal'] ?? '0';

        if (empty($namaPemesan) || empty($whatsapp)) {
            $_SESSION['cart_error'] = "Nama dan nomor WhatsApp wajib diisi.";
            header("Location: ?page=cart");
            exit;
        }

        $order = new Order(
            0, '',
            $listItemOrder, $subTotal,
            'Pending',
            json_encode([[
                'status'   => 'Pending',
                'detail'   => 'Pesanan masuk via keranjang belanja',
                'datetime' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]]),
            $namaPemesan, $alamatPemesan, $whatsapp, $instagram,
            new \DateTime(), new \DateTime()
        );

        $saved = $this->orderService->saveOrder($order);

        header("Location: ?page=cart&order=" . urlencode($saved->getOrderNumber()));
        exit;
    }
}
