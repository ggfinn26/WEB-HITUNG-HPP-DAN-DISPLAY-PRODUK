<?php
// Pastikan variabel $order tersedia dari OrderController::print()
if (!isset($order)) {
    die("Data pesanan tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Invoice - <?php echo htmlspecialchars($order->getOrderNumber()); ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;family=Montserrat:wght@600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        @media print {
            body {
                background-color: white !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface": "#f8f9ff",
                        "secondary-fixed": "#ffdcc3",
                        "on-error-container": "#93000a",
                        "surface-container-high": "#dce9ff",
                        "inverse-on-surface": "#eaf1ff",
                        "outline-variant": "#c3c6d1",
                        "tertiary-container": "#003a3a",
                        "on-tertiary-fixed-variant": "#004f4f",
                        "surface-dim": "#cbdbf5",
                        "on-tertiary-container": "#47aaaa",
                        "secondary": "#904d00",
                        "primary-fixed": "#d5e3ff",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#603100",
                        "inverse-surface": "#213145",
                        "tertiary-fixed-dim": "#76d6d5",
                        "primary": "#001e40",
                        "on-secondary": "#ffffff",
                        "inverse-primary": "#a7c8ff",
                        "tertiary-fixed": "#93f2f2",
                        "error": "#ba1a1a",
                        "outline": "#737780",
                        "surface-container-low": "#eff4ff",
                        "secondary-container": "#fd8b00",
                        "surface-tint": "#3a5f94",
                        "error-container": "#ffdad6",
                        "surface-container": "#e5eeff",
                        "on-secondary-fixed": "#2f1500",
                        "on-primary-fixed-variant": "#1f477b",
                        "primary-fixed-dim": "#a7c8ff",
                        "tertiary": "#002323",
                        "primary-container": "#003366",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed-variant": "#6e3900",
                        "on-primary-fixed": "#001b3c",
                        "on-primary": "#ffffff",
                        "surface-bright": "#f8f9ff",
                        "on-tertiary-fixed": "#002020",
                        "on-surface-variant": "#43474f",
                        "surface-variant": "#d3e4fe",
                        "secondary-fixed-dim": "#ffb77d",
                        "on-background": "#0b1c30",
                        "on-primary-container": "#799dd6",
                        "background": "#f8f9ff",
                        "on-surface": "#0b1c30",
                        "surface-container-highest": "#d3e4fe",
                        "on-error": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "unit": "8px",
                        "gutter": "24px",
                        "container-max": "1280px",
                        "margin-desktop": "48px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Montserrat"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Montserrat"],
                        "body-md": ["Inter"],
                        "display-lg": ["Montserrat"],
                        "headline-lg-mobile": ["Montserrat"],
                        "label-sm": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    "fontSize": {
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "700" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "700" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-on-surface font-body-md antialiased print:bg-white min-h-screen flex flex-col justify-center items-center p-margin-mobile md:p-margin-desktop">
<!-- Print Canvas -->
<main class="w-full max-w-[800px] bg-surface-container-lowest shadow-sm rounded-xl print:shadow-none print:rounded-none overflow-hidden border border-surface-variant flex flex-col">
<!-- Header -->
<header class="p-gutter md:p-[40px] flex justify-between items-start border-b border-surface-variant bg-surface-bright print:bg-white">
<div class="flex items-center gap-unit">
<img alt="Arunga Mascot Logo" class="w-16 h-16 rounded-full object-cover shadow-sm" src="favicon.png"/>
<div>
<h1 class="text-headline-md font-headline-md text-primary-container">Mbu Titip</h1>
<p class="text-label-sm font-label-sm text-on-surface-variant mt-1">by Arunga Arungi Dunia</p>
</div>
</div>
<div class="text-right">
<h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">INVOICE</h2>
<div class="mt-2 text-label-md font-label-md text-on-surface-variant flex flex-col gap-1">
<p><span class="font-semibold">Invoice #:</span> <?php echo htmlspecialchars($order->getOrderNumber()); ?></p>
<p><span class="font-semibold">Date:</span> <?php echo $order->getCreatedAt()->format('M d, Y'); ?></p>
</div>
</div>
</header>
<!-- Customer & Payment Status -->
<section class="p-gutter md:p-[40px] flex justify-between items-start bg-surface-container-lowest print:bg-white">
<div>
<h3 class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider mb-2">Billed To:</h3>
<p class="text-body-md font-body-md font-semibold text-on-surface"><?php echo htmlspecialchars($order->getNamaPemesan()); ?></p>
<p class="text-body-md font-body-md text-on-surface-variant mt-1"><?php echo nl2br(htmlspecialchars($order->getAlamatPemesan())); ?></p>
<?php if($order->getInstagramUserNamePemesan()): ?>
<p class="text-body-md font-body-md text-on-surface-variant mt-1">IG: @<?php echo htmlspecialchars($order->getInstagramUserNamePemesan()); ?></p>
<?php endif; ?>
<p class="text-body-md font-body-md text-on-surface-variant mt-1">WA: <?php echo htmlspecialchars($order->getWhatsappPemesan()); ?></p>
</div>
<div class="flex flex-col items-end">
<?php
    $status = $order->getOrderStatus();
    $historyStr = $order->getStatusHistory();
    $historyArr = $historyStr ? json_decode($historyStr, true) : [];
    $lastItem = !empty($historyArr) ? end($historyArr) : null;
    $savedColor = $lastItem['color'] ?? null;
    
    // Set colors based on status history
    $bgColorClass = 'bg-primary-container/10 border-primary-container/20 text-primary-container';
    $iconName = 'info';
    
    if ($savedColor === 'green') {
        $bgColorClass = 'bg-tertiary-container/10 border-tertiary-container/20 text-tertiary-container';
        $iconName = 'check_circle';
    } elseif ($savedColor === 'amber') {
        $bgColorClass = 'bg-secondary-container/10 border-secondary-container/20 text-secondary-container';
        $iconName = 'pending';
    } elseif ($savedColor === 'red') {
        $bgColorClass = 'bg-error-container/10 border-error-container/20 text-error';
        $iconName = 'cancel';
    }
?>
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border <?php echo $bgColorClass; ?>">
<span class="material-symbols-outlined text-[20px]"><?php echo $iconName; ?></span>
<span class="text-label-md font-label-md font-bold uppercase tracking-wide"><?php echo htmlspecialchars($status); ?></span>
</div>
<?php if (!empty($lastItem['detail'])): ?>
<p class="text-label-sm font-label-sm text-on-surface-variant mt-2 max-w-[200px] text-right italic">
    "<?php echo htmlspecialchars($lastItem['detail']); ?>"
</p>
<?php endif; ?>
</div>
</section>
<!-- Itemized Table -->
<?php 
    $itemsJson = $order->getListItemOrder();
    $items = $itemsJson ? json_decode($itemsJson, true) : [];
?>
<section class="px-gutter md:px-[40px] pb-gutter md:pb-[40px] bg-surface-container-lowest print:bg-white">
<div class="overflow-x-auto rounded-lg border border-surface-variant">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container text-label-md font-label-md text-primary">
<tr>
<th class="py-3 px-4 font-semibold border-b border-surface-variant">Product Description</th>
<th class="py-3 px-4 font-semibold border-b border-surface-variant text-center">Qty</th>
<th class="py-3 px-4 font-semibold border-b border-surface-variant text-right">Price</th>
<th class="py-3 px-4 font-semibold border-b border-surface-variant text-right">Subtotal</th>
</tr>
</thead>
<tbody class="text-body-md font-body-md text-on-surface divide-y divide-surface-variant">
    <?php if (empty($items) || !is_array($items)): ?>
    <tr>
        <td colspan="4" class="py-6 px-4 text-center text-on-surface-variant italic">Tidak ada barang</td>
    </tr>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
        <tr class="hover:bg-surface-bright transition-colors">
        <td class="py-4 px-4">
        <p class="font-medium"><?php echo htmlspecialchars($item['name'] ?? '-'); ?></p>
        </td>
        <td class="py-4 px-4 text-center"><?php echo (int)($item['qty'] ?? 1); ?></td>
        <td class="py-4 px-4 text-right">Rp. <?php echo number_format((float)($item['price'] ?? 0), 0, ',', '.'); ?></td>
        <td class="py-4 px-4 text-right font-medium">Rp. <?php echo number_format((float)(($item['price'] ?? 0) * ($item['qty'] ?? 1)), 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table>
</div>
</section>
<!-- Totals -->
<section class="px-gutter md:px-[40px] pb-gutter md:pb-[40px] flex justify-end bg-surface-container-lowest print:bg-white">
<div class="w-full md:w-1/2 rounded-lg bg-surface-bright p-6 border border-surface-variant">
<div class="flex justify-between items-center">
<span class="text-headline-md font-headline-md text-primary">Total</span>
<span class="text-headline-md font-headline-md text-primary">Rp. <?php echo number_format((float)$order->getSubTotal(), 0, ',', '.'); ?></span>
</div>
</div>
</section>
<!-- Footer -->
<footer class="mt-auto bg-primary-container text-on-primary-container p-gutter md:p-[32px] print:bg-white print:text-on-surface print:border-t print:border-surface-variant">
<div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
<div>
<p class="text-label-md font-label-md font-semibold mb-1">Thank you for exploring with us!</p>
<p class="text-label-sm font-label-sm opacity-80 print:opacity-100">Questions? Contact us at ig @arungaarungidunia</p>
</div>
<div class="flex items-center gap-4 text-label-sm font-label-sm opacity-80 print:opacity-100">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">call</span> +62 895-3801-23352</span>
</div>
</div>
</footer>
</main>
<!-- Print Action (Hidden in print) -->
<div class="mt-8 print:hidden no-print flex gap-4">
<button class="bg-surface-variant hover:bg-outline-variant text-on-surface px-6 py-3 rounded-lg font-label-md text-label-md flex items-center gap-2 transition-colors shadow-sm hover:shadow-md" onclick="window.close()">
<span class="material-symbols-outlined">close</span> Tutup
</button>
<button class="bg-primary hover:bg-primary-container text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md flex items-center gap-2 transition-colors shadow-sm hover:shadow-md" onclick="window.print()">
<span class="material-symbols-outlined">print</span> Print Invoice
</button>
</div>
<script>
    // Auto print if needed
    window.onload = function() {
        // window.print();
    }
</script>
</body></html>
