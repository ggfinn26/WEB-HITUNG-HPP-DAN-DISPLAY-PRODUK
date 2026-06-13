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
        if (isset($_SESSION['cart_success'])) {
            $success = $_SESSION['cart_success'];
            unset($_SESSION['cart_success']);
        }

        $adminWa = $_ENV['ADMIN_WA'] ?? '62895380123352';
        $adminIg = $_ENV['ADMIN_IG'] ?? 'arungaarungidunia';
        $waUrl   = null;
        $igMsg   = null;

        if ($success) {
            $items     = json_decode($success['items'], true) ?? [];
            $itemsText = '';
            foreach ($items as $item) {
                $qty      = (int)($item['qty'] ?? 1);
                $price    = (float)($item['price'] ?? 0);
                $name     = $item['name'] ?? '';
                $subtotal = 'Rp ' . number_format($qty * $price, 0, ',', '.');
                $itemsText .= "• {$name} x {$qty} — {$subtotal}\n";
            }
            $total = 'Rp ' . number_format((float)$success['subTotal'], 0, ',', '.');
            $nama  = $success['namaPemesan'];
            $wa    = $success['whatsapp'] ?? '-';
            $alamat = $success['alamat'] ?? '-';
            $ig    = $success['instagram'] ?? '';

            $e = [
                'wave'  => "\u{1F44B}",
                'receipt' => "\u{1F9FE}",
                'box'   => "\u{1F4E6}",
                'money' => "\u{1F4B0}",
                'person'=> "\u{1F464}",
                'phone' => "\u{1F4F1}",
                'house' => "\u{1F3E0}",
                'cam'   => "\u{1F4F8}",
                'pray'  => "\u{1F64F}",
            ];

            $waMsg = "Halo Mbu Titip Arunga! {$e['wave']}\n\n"
                   . "Saya mau konfirmasi pesanan:\n\n"
                   . "{$e['receipt']} *No. Order: {$success['orderNumber']}*\n\n"
                   . "{$e['box']} *Pesanan:*\n{$itemsText}\n"
                   . "{$e['money']} *Total: {$total}*\n\n"
                   . "{$e['person']} *Nama:* {$nama}\n"
                   . "{$e['phone']} *WA:* {$wa}\n"
                   . ($alamat ? "{$e['house']} *Alamat:* {$alamat}\n" : '')
                   . "\nMohon konfirmasi ketersediaan ya! Terima kasih {$e['pray']}";

            $igMsg = "Halo Mbu Titip Arunga! {$e['wave']}\n\n"
                   . "Saya mau konfirmasi pesanan:\n\n"
                   . "{$e['receipt']} No. Order: {$success['orderNumber']}\n\n"
                   . "{$e['box']} Pesanan:\n{$itemsText}\n"
                   . "{$e['money']} Total: {$total}\n\n"
                   . "{$e['person']} Nama: {$nama}\n"
                   . "{$e['phone']} WA: {$wa}\n"
                   . ($alamat ? "{$e['house']} Alamat: {$alamat}\n" : '')
                   . ($ig ? "{$e['cam']} Instagram: @{$ig}\n" : '')
                   . "\nMohon konfirmasi ketersediaan ya! Terima kasih {$e['pray']}";

            $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($waMsg);
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

        $_SESSION['cart_success'] = [
            'orderNumber'  => $saved->getOrderNumber(),
            'namaPemesan'  => $namaPemesan,
            'whatsapp'     => $whatsapp,
            'alamat'       => $alamatPemesan,
            'instagram'    => $instagram,
            'items'        => $listItemOrder,
            'subTotal'     => $subTotal,
        ];

        header("Location: ?page=cart&action=success");
        exit;
    }
}
