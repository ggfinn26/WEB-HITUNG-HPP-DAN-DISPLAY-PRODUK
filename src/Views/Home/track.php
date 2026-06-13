<?php if (isset($error) || !isset($order)): ?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tracking Error - Mbu Titip</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f9ff] text-[#0b1c30] min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-surface p-8 rounded-xl shadow-sm border border-[#c3c6d1] text-center max-w-md w-full">
        <div class="text-6xl mb-4">🔍</div>
        <h1 class="text-2xl font-bold text-[#ba1a1a] mb-2">Pencarian Gagal</h1>
        <p class="text-[#43474f] mb-6"><?php echo $error ?? 'Pesanan tidak ditemukan.'; ?></p>
        <a href="?page=home" class="inline-block bg-[#001e40] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#003366] transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Order Tracking Result - Mbu Titip</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<style>
        .flight-path-stepper::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 24px;
            bottom: 24px;
            width: 2px;
            background-color: var(--color-on-tertiary-fixed-variant);
            z-index: 0;
        }

        .timeline-item-active .timeline-dot {
            background-color: var(--color-secondary-container);
            border-color: var(--color-secondary-container);
        }

        .timeline-item-past .timeline-dot {
            background-color: var(--color-tertiary-container);
            border-color: var(--color-tertiary-container);
        }

        .timeline-item-future .timeline-dot {
            background-color: var(--color-surface);
            border-color: var(--color-outline-variant);
            border-width: 2px;
        }

        .timeline-line {
            position: absolute;
            top: 32px;
            bottom: -16px;
            left: 15px;
            width: 2px;
            background-color: var(--color-outline-variant);
            z-index: -1;
        }

        .timeline-line-active {
            background-color: var(--color-tertiary-container);
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body-md text-body-md antialiased pt-16 pb-24 md:pb-0 min-h-screen flex flex-col">
<!-- TopAppBar -->
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-margin-mobile md:px-margin-desktop h-16 bg-surface shadow-sm transition-all duration-300">
<div class="flex items-center gap-4">
<a href="?page=home"><img alt="Mbu Titip Logo" class="h-10 w-10 rounded-DEFAULT object-cover" src="logo_transparent.svg"/></a>
<span class="text-headline-md font-headline-md font-bold text-primary"><a href="?page=home">Mbu Titip</a></span>
</div>
<div class="flex items-center gap-4">
<a href="?page=home" class="text-on-surface-variant hover:text-secondary transition-colors" title="Home">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">home</span>
</a>
</div>
</header>
<!-- Main Content -->
<main class="flex-grow w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8 grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Left Column: Tracking Status & History -->
<div class="lg:col-span-8 flex flex-col gap-6">
<!-- Hero Status Card (Glassmorphism) -->
<section class="glass-panel rounded-xl p-6 shadow-lg relative overflow-hidden bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBuY0Vr5OsIS6lc0LVD7pkMg2PWhZZiXST7TP1Fr1gxy5-4bREqZOk9YYBMAHSG3Bk4sl5dF12Ls_WYjDrGhU_35gMfHapiNxp4IW3HTXNiM2UJaNR8zyBCizdLht7GW__wgGkoGTyId4ukChlSztve8UtA9TiAH43efuWonQeKKDDFvwIWelcMe79UySCGF9m_ETllaka_XlfDpkNS1mZYaHd8iV_0mPsKfTQ1d71QQ3Zs-BN4pB_2OiAkrRT_LviFnWsP9aD75A');">
<div class="absolute inset-0 bg-surface-container backdrop-blur-md"></div>
<div class="relative z-10">
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
<div>
<p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider mb-1">Order #<?php echo htmlspecialchars($order->getOrderNumber()); ?></p>
<h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary mb-2"><?php echo htmlspecialchars($order->getOrderStatus()); ?></h1>
<?php 
    $historyStr = $order->getStatusHistory();
    $history = $historyStr ? json_decode($historyStr, true) : [];
    $latestDetail = !empty($history) ? end($history)['detail'] ?? '' : '';
?>
<?php if($latestDetail): ?>
<div class="flex items-center gap-2 mt-2">
<span class="inline-flex items-center gap-1 bg-surface-variant text-on-surface-variant px-3 py-1 rounded-full text-label-sm font-label-sm">
<span class="material-symbols-outlined text-[16px]">info</span>
    <?php echo htmlspecialchars($latestDetail); ?>
</span>
</div>
<?php endif; ?>
</div>
<div class="text-left md:text-right">
<p class="text-label-md font-label-md text-on-surface-variant mb-1">Pesanan Dibuat</p>
<p class="text-headline-md font-headline-md text-secondary-container"><?php echo $order->getCreatedAt()->format('d M Y'); ?></p>
</div>
</div>
<!-- Progress Stepper -->
<div class="relative w-full h-2 bg-surface-container-high rounded-full mt-8 mb-4">
<div class="absolute top-0 left-0 h-full bg-on-tertiary-container rounded-full" style="width: 65%;"></div>
<div class="absolute top-1/2 -translate-y-1/2 left-[65%] -translate-x-1/2 bg-surface rounded-full p-1 shadow-md border-2 border-on-tertiary-container">
<span class="material-symbols-outlined text-secondary-container text-[20px]" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
</div>
</div>
<div class="flex justify-between text-label-sm font-label-sm text-on-surface-variant">
<span>Mbu Titip</span>
<span>Pelanggan</span>
</div>
</div>
</section>

<!-- Tracking History Timeline -->
<section class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05)] border border-surface-container-highest">
<h2 class="text-headline-md font-headline-md text-primary mb-6 flex items-center gap-2">
<span class="material-symbols-outlined text-secondary">history</span>
    Tracking History
</h2>
<div class="relative ml-4">
<?php if (!empty($history)): ?>
    <?php 
        // Balikkan history agar yang terbaru di atas
        $historyRev = array_reverse($history); 
        $count = count($historyRev);
    ?>
    <?php foreach ($historyRev as $index => $item): ?>
        <?php 
            $isActive = ($index === 0);
            $isLast = ($index === $count - 1);
            $itemClass = $isActive ? 'timeline-item-active' : 'timeline-item-past';
            
            // Tentukan icon berdasarkan color
            $icon = 'check_circle';
            $color = $item['color'] ?? 'blue';
            if ($color === 'green') $icon = 'check_circle';
            elseif ($color === 'amber') $icon = 'pending';
            elseif ($color === 'red') $icon = 'cancel';
            elseif ($color === 'blue') $icon = 'info';
            
            $dt = new \DateTime($item['datetime']);
        ?>
        <div class="relative pl-10 <?php echo !$isLast ? 'pb-8' : ''; ?> <?php echo $itemClass; ?>">
            <?php if (!$isLast): ?>
            <div class="timeline-line timeline-line-active"></div>
            <?php endif; ?>
            <div class="absolute left-0 top-1 w-8 h-8 -ml-4 rounded-full flex items-center justify-center timeline-dot shadow-sm z-10 <?php echo $isActive ? 'text-on-secondary-container' : 'text-on-tertiary'; ?>">
                <span class="material-symbols-outlined text-[18px]"><?php echo $icon; ?></span>
            </div>
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-1 md:gap-4">
                <div>
                    <h3 class="text-body-lg font-body-lg text-on-surface font-semibold"><?php echo htmlspecialchars($item['status']); ?></h3>
                    <?php if(!empty($item['detail'])): ?>
                    <p class="text-body-md font-body-md text-on-surface-variant mt-1"><?php echo htmlspecialchars($item['detail']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-label-md font-label-md text-outline whitespace-nowrap md:text-right mt-2 md:mt-0">
                    <span><?php echo $dt->format('d M Y'); ?></span><br/>
                    <span><?php echo $dt->format('H:i'); ?> WIB</span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-on-surface-variant italic">Belum ada riwayat pesanan.</p>
<?php endif; ?>
</div>
</section>
</div>

<!-- Right Column: Order Details -->
<aside class="lg:col-span-4 flex flex-col gap-6">
<!-- Items Card -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05)] border border-surface-container-highest">
<h3 class="text-headline-md font-headline-md text-primary mb-4">Order Details</h3>
<div class="flex flex-col gap-4">
<?php 
    $itemsJson = $order->getListItemOrder();
    $items = $itemsJson ? json_decode($itemsJson, true) : [];
?>
<?php if(empty($items)): ?>
    <p class="text-sm text-on-surface-variant italic">Tidak ada rincian barang</p>
<?php else: ?>
    <?php foreach ($items as $item): ?>
    <div class="flex gap-4 p-3 bg-surface-bright rounded-lg border border-surface-container">
        <div class="w-12 h-12 rounded-md bg-surface-dim flex items-center justify-center flex-shrink-0 text-primary opacity-50">
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <div class="flex-grow">
            <p class="text-body-md font-body-md font-semibold text-on-surface line-clamp-2"><?php echo htmlspecialchars($item['name'] ?? '-'); ?></p>
            <p class="text-label-md font-label-md text-on-surface-variant">Qty: <?php echo (int)($item['qty'] ?? 1); ?> • Rp. <?php echo number_format((float)($item['price'] ?? 0), 0, ',', '.'); ?></p>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<div class="mt-6 pt-4 border-t border-surface-container-highest">
    <div class="flex justify-between items-center mb-2">
        <span class="text-body-md font-body-md text-on-surface-variant">Total Tagihan</span>
        <span class="text-headline-md font-headline-md text-secondary-container">Rp. <?php echo number_format((float)$order->getSubTotal(), 0, ',', '.'); ?></span>
    </div>
</div>
</div>

<!-- Help Action -->
<a href="https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>" target="_blank" class="w-full py-3 px-4 rounded-lg border-2 border-on-tertiary-container text-on-tertiary-container font-label-md font-semibold hover:bg-inverse-on-surface transition-colors flex items-center justify-center gap-2">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">support_agent</span>
    Need Help with this Order?
</a>
</aside>
</main>

<!-- BottomNavBar (Mobile Only) -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center py-2 px-4 md:hidden bg-surface-container-lowest shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] rounded-t-xl transition-transform duration-300">
<a class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high rounded-full transition-colors group" href="?page=home">
<span class="material-symbols-outlined mb-1 group-hover:text-secondary-container transition-colors" style="font-variation-settings: 'FILL' 0;">home</span>
<span class="text-label-sm font-label-sm">Home</span>
</a>
<a class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 scale-90 duration-200 shadow-sm" href="#">
<span class="material-symbols-outlined mb-1" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
<span class="text-label-sm font-label-sm font-bold">Tracking</span>
</a>
</nav>

<!-- Footer (Desktop Only) -->
<footer class="hidden md:flex flex-col items-center w-full py-12 px-margin-desktop mt-auto bg-primary text-on-primary">
<div class="flex flex-col md:flex-row justify-between w-full max-w-container-max gap-8 mb-8">
<div class="flex items-center gap-4">
<span class="text-headline-md font-headline-md text-on-primary font-bold">Mbu Titip</span>
</div>
<div class="flex gap-6 text-label-sm font-label-sm">
<a class="text-on-primary/80 hover:text-secondary-fixed-dim transition-colors" href="https://instagram.com/arungaarungidunia">Instagram</a>
<a class="text-on-primary/80 hover:text-secondary-fixed-dim transition-colors" href="https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>">Help Center</a>
</div>
</div>
<div class="text-body-md font-body-md text-on-primary/60 w-full max-w-container-max text-center border-t border-on-primary/20 pt-8">
            © 2024 Mbu Titip by Arunga Arungi Dunia. All rights reserved.
        </div>
</footer>
<script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>
</body></html>
<?php endif; ?>
