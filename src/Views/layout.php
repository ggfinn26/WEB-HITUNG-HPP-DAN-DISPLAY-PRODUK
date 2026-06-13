<?php
// ── SEO helpers ───────────────────────────────────────────────────────────────
$_seoScheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_seoHost    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_seoBaseUrl = $_seoScheme . '://' . $_seoHost;
$_seoUri     = $_SERVER['REQUEST_URI'] ?? '/';

// Pages that should never be indexed
$_adminPages = ['orders', 'products', 'hpp', 'laporan', 'auth', 'track'];
$_curPage    = $_GET['page'] ?? 'home';

$_seoTitle   = $title       ?? 'Mbu Titip by Arunga Arungi Dunia';
$_seoDesc    = $description ?? 'Jasa titip terpercaya dari berbagai penjuru Nusantara. Temukan barang-barang favorit keluarga dengan layanan personal shopper terbaik.';
$_seoRobots  = $metaRobots  ?? (in_array($_curPage, $_adminPages) ? 'noindex, nofollow' : 'index, follow');
$_seoCanon   = $canonical   ?? ($_seoBaseUrl . $_seoUri);
$_seoOgImg   = $ogImage     ?? ($_seoBaseUrl . '/favicon.png');
$_seoOgType  = $ogType      ?? 'website';
$_seoLd      = $structuredData ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- ── Primary SEO ── -->
    <title><?= htmlspecialchars($_seoTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($_seoDesc) ?>">
    <meta name="robots" content="<?= htmlspecialchars($_seoRobots) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($_seoCanon) ?>">

    <!-- ── Open Graph ── -->
    <meta property="og:type"        content="<?= htmlspecialchars($_seoOgType) ?>">
    <meta property="og:site_name"   content="Mbu Titip by Arunga Arungi Dunia">
    <meta property="og:locale"      content="id_ID">
    <meta property="og:title"       content="<?= htmlspecialchars($_seoTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($_seoDesc) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($_seoCanon) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($_seoOgImg) ?>">
    <meta property="og:image:alt"   content="<?= htmlspecialchars($_seoTitle) ?>">

    <!-- ── Twitter Card ── -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($_seoTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($_seoDesc) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($_seoOgImg) ?>">

    <!-- ── Structured Data ── -->
    <?php if ($_seoLd): ?>
    <script type="application/ld+json"><?= $structuredData ?></script>
    <?php endif; ?>

    <!-- ── Favicon ── -->
    <link rel="icon"             type="image/png" href="favicon.png">
    <link rel="apple-touch-icon"                  href="favicon.png">

    <!-- ── Preconnect (performance + SEO Core Web Vitals) ── -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- ── CSS ── -->
    <link rel="stylesheet" href="css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800&family=Inter:wght@400;500;600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

    <!-- ── JS ── -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
    <script src="https://d3js.org/d3.v7.min.js" defer></script>
    <script src="https://unpkg.com/topojson-client@3" defer></script>
</head>
<body class="bg-background text-on-surface selection:bg-secondary-container selection:text-on-secondary-container min-h-screen flex flex-col font-sans antialiased relative">
    
    <!-- Header (TopNavBar) -->
    <header class="bg-surface-container dark:bg-primary-container/60 backdrop-blur-xl border-b border-outline-variant/30 dark:border-outline/20 shadow-sm docked w-full top-0 sticky z-50 no-print">
        <nav class="flex justify-between items-center w-full px-6 py-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="?page=home" class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim hover:opacity-80 transition-opacity flex items-center">
                    <img src="favicon.png" alt="Logo" class="inline-block w-10 h-10 rounded-full mr-3 object-cover shadow-sm" />
                    <span class="hidden sm:inline">Mbu Titip</span>
                </a>
            </div>
            
            <div class="hidden lg:flex items-center gap-8">
                <a class="text-secondary dark:text-secondary-fixed font-bold border-b-2 border-secondary font-label-md text-label-md hover:text-secondary transition-colors duration-300" href="?page=home">Katalog</a>
                <a class="text-on-surface-variant dark:text-on-primary-fixed-variant font-medium font-label-md text-label-md hover:text-secondary transition-colors duration-300" href="?page=home#lacak-pesanan">Lacak Pesanan</a>
                
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <span class="w-px h-6 bg-outline-variant/50"></span>
                    <a href="?page=products" class="font-medium font-label-md text-label-md hover:text-primary transition-colors text-slate-600 dark:text-slate-300">Produk</a>
                    <a href="?page=orders" class="font-medium font-label-md text-label-md hover:text-primary transition-colors text-slate-600 dark:text-slate-300">Order</a>
                    <a href="?page=hpp" class="font-medium font-label-md text-label-md hover:text-primary transition-colors text-slate-600 dark:text-slate-300">HPP</a>
                    <a href="?page=sesi" class="font-medium font-label-md text-label-md hover:text-primary transition-colors text-slate-600 dark:text-slate-300">Sesi</a>
                    <a href="?page=laporan" class="font-medium font-label-md text-label-md hover:text-primary transition-colors text-slate-600 dark:text-slate-300">Laporan</a>
                    <a href="?page=auth&action=logout" class="font-medium font-label-md text-label-md text-red-500 hover:text-red-600 transition-colors">Keluar</a>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-3">
                <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
                    <a href="?page=auth&action=login" class="text-on-surface-variant text-sm hover:text-primary font-medium hidden sm:block">Admin Login</a>
                <?php endif; ?>

                <div class="relative group hidden md:block">
                    <select id="theme-selector" class="appearance-none bg-surface-container-low text-on-surface text-sm rounded-lg px-3 py-2 border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/50 cursor-pointer" onchange="applyTheme(this.value)">
                        <option value="system">Auto Mode</option>
                        <option value="dark">Dark Mode</option>
                        <option value="light">Light Mode</option>
                        <option value="pink">Pink Theme</option>
                        <option value="sky-blue">Biru Langit</option>
                        <option value="coffee">Coffee</option>
                        <option value="matcha">Matcha</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-on-surface">
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </div>
                </div>
                <a href="?page=home#katalog" class="bg-secondary-container text-on-secondary-container px-4 sm:px-6 py-2 rounded-lg font-label-md text-label-md font-bold hover:scale-95 active:scale-90 transition-all shadow-md hidden sm:inline-flex">
                    Mulai Belanja
                </a>
                <button id="mobile-menu-btn" aria-label="Buka menu" aria-expanded="false"
                        class="lg:hidden p-2 rounded-xl text-on-surface hover:bg-surface-container-high active:bg-outline-variant/30 transition-colors">
                    <span class="material-symbols-outlined" id="mobile-menu-icon">menu</span>
                </button>
            </div>
        </nav>

        <!-- Mobile navigation drawer -->
        <div id="mobile-nav"
             class="lg:hidden hidden border-t border-outline-variant/30 bg-surface-container/95 backdrop-blur-xl px-4 pb-4 pt-2 space-y-1">
            <a href="?page=home"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-secondary font-bold hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">storefront</span>Katalog
            </a>
            <a href="?page=home#lacak-pesanan"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant font-medium hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">package_2</span>Lacak Pesanan
            </a>

            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <div class="my-2 h-px bg-outline-variant/40 mx-2"></div>
                <p class="px-4 pt-1 pb-0.5 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/60">Admin</p>
                <a href="?page=products"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>Produk
                </a>
                <a href="?page=orders"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>Order
                </a>
                <a href="?page=hpp"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">calculate</span>HPP
                </a>
                <a href="?page=sesi"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">analytics</span>Sesi Trip
                </a>
                <a href="?page=laporan"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">bar_chart</span>Laporan
                </a>
                <div class="my-2 h-px bg-outline-variant/40 mx-2"></div>
                <a href="?page=auth&action=logout"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>Keluar
                </a>
            <?php else: ?>
                <div class="my-2 h-px bg-outline-variant/40 mx-2"></div>
                <a href="?page=auth&action=login"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant font-medium hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[20px]">lock</span>Admin Login
                </a>
            <?php endif; ?>

            <div class="pt-2 px-1">
                <select id="mobile-theme-selector"
                        class="w-full appearance-none bg-surface-container-low text-on-surface text-sm rounded-xl px-4 py-3 border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/40 cursor-pointer"
                        onchange="applyTheme(this.value)">
                    <option value="system">Auto Mode</option>
                    <option value="dark">Dark Mode</option>
                    <option value="light">Light Mode</option>
                    <option value="pink">Pink Theme</option>
                    <option value="sky-blue">Biru Langit</option>
                    <option value="coffee">Coffee</option>
                    <option value="matcha">Matcha</option>
                </select>
            </div>

            <a href="?page=home#katalog"
               class="flex items-center justify-center gap-2 bg-secondary-container text-on-secondary-container mx-1 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity sm:hidden">
                <span class="material-symbols-outlined text-[20px]">shopping_bag</span>Mulai Belanja
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full relative z-10 p-6 md:p-10 max-w-7xl mx-auto">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-lowest dark:bg-inverse-surface border-t border-outline-variant dark:border-outline w-full mt-auto no-print">
        <div class="flex flex-col items-center text-center px-6 py-12 max-w-2xl mx-auto">
            <span class="font-headline-md text-headline-md font-extrabold text-primary dark:text-primary-fixed-dim mb-4 flex items-center justify-center gap-2">
                <img src="favicon.png" alt="Logo" class="w-8 h-8 rounded-full object-cover" />
                Mbu Titip
            </span>
            <p class="font-body-md text-body-md text-on-surface-variant dark:text-surface-variant mb-6">Membawa keajaiban Nusantara ke depan pintu rumah Anda dengan layanan personal shopper terpercaya untuk keluarga.</p>
            
            <div class="flex justify-center gap-4">
                <a class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center hover:text-primary hover:scale-110 transition-all text-primary shadow-sm" 
                   href="https://instagram.com/arungaarungidunia" target="_blank" title="Instagram Arunga">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <a class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center hover:text-primary hover:scale-110 transition-all text-primary shadow-sm" 
                   href="https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>" target="_blank" title="WhatsApp Admin">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="py-6 border-t border-outline-variant/30 text-center max-w-7xl mx-auto">
            <p class="font-label-sm text-label-sm text-on-surface-variant dark:text-surface-variant">© <?php echo date('Y'); ?> Arunga Arungi Dunia. Temanmu Mengarungi Nusantara.</p>
        </div>
    </footer>
    
    <script>
        // Theme Management Logic (Preserved for Admin Pages backwards compatibility)
        function applyTheme(themeName) {
            document.documentElement.classList.remove('theme-dark', 'theme-pink', 'theme-sky-blue', 'theme-chocolate', 'theme-coffee', 'theme-matcha', 'dark');
            
            if (themeName === 'system' || !themeName) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('theme-dark', 'dark');
                }
                localStorage.removeItem('app-theme');
            } else {
                if(themeName !== 'white' && themeName !== 'light') {
                    if (themeName === 'dark') {
                        document.documentElement.classList.add('theme-dark', 'dark');
                    } else {
                        document.documentElement.classList.add('theme-' + themeName);
                    }
                }
                localStorage.setItem('app-theme', themeName);
            }
        }

        const savedTheme = localStorage.getItem('app-theme');
        applyTheme(savedTheme || 'system');

        // Sync desktop theme selector
        const selector = document.getElementById('theme-selector');
        if (selector) selector.value = savedTheme || 'system';

        // Sync mobile theme selector
        const mobileSelector = document.getElementById('mobile-theme-selector');
        if (mobileSelector) mobileSelector.value = savedTheme || 'system';

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (!localStorage.getItem('app-theme')) applyTheme('system');
        });

        // Mobile hamburger menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');
        if (mobileMenuBtn && mobileNav) {
            mobileMenuBtn.addEventListener('click', () => {
                const isOpen = !mobileNav.classList.contains('hidden');
                mobileNav.classList.toggle('hidden');
                mobileMenuIcon.textContent = isOpen ? 'menu' : 'close';
                mobileMenuBtn.setAttribute('aria-expanded', String(!isOpen));
            });
        }
    </script>
</body>
</html>
