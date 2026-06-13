<!-- Hero Section for Catalog -->
<section class="relative overflow-hidden pt-32 pb-16 px-4 md:px-margin-desktop bg-surface-container-low rounded-b-[3rem] shadow-sm mb-10">
    <div class="max-w-7xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary font-bold text-sm mb-6">
            <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
            Jastip Arunga
        </div>
        <h1 class="font-display-lg text-4xl md:text-5xl font-bold text-primary mb-4">Katalog Produk Lengkap</h1>
        <p class="font-body-lg text-lg text-on-surface-variant max-w-2xl mx-auto">
            Jelajahi seluruh koleksi barang jastip favorit keluarga dari berbagai pelosok negeri. 
        </p>
    </div>
</section>

<!-- Product Catalog Section -->
<section id="katalog-lengkap" class="py-12 mb-20 mx-4 md:mx-0">
    <div class="max-w-7xl mx-auto px-6 md:px-margin-desktop">
        <?php if (empty($products)): ?>
            <div class="glass-panel p-16 text-center rounded-[2rem] border-2 border-dashed border-outline-variant/50 max-w-3xl mx-auto">
                <div class="text-6xl mb-6">🛍️</div>
                <h3 class="text-2xl font-bold text-primary mb-3">Belum Ada Produk</h3>
                <p class="text-on-surface-variant text-lg">Admin sedang berkeliling nusantara mencari barang impianmu.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($products as $product): ?>
                    <!-- Product Card -->
                    <div class="group bg-surface rounded-3xl overflow-hidden shadow-sm hover:-translate-y-2 hover:shadow-xl transition-all duration-300 border border-outline-variant/20">
                        <div class="relative h-64 overflow-hidden bg-surface-container-high flex items-center justify-center">
                            <?php if ($product->getImageUrl()): ?>
                                <img src="<?= htmlspecialchars($product->getImageUrl()) ?>" alt="<?= htmlspecialchars($product->getName()) ?>" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary-container/10 group-hover:scale-110 transition-transform duration-500"></div>
                                <span class="text-6xl relative z-10 drop-shadow-sm group-hover:scale-110 transition-transform duration-500">📦</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-8 flex flex-col h-[calc(100%-16rem)]">
                            <h3 class="font-headline-md text-xl font-bold text-on-surface mb-3 line-clamp-2" title="<?= htmlspecialchars($product->getName()) ?>">
                                <?= htmlspecialchars($product->getName()) ?>
                            </h3>
                            <p class="font-body-md text-on-surface-variant mb-6 line-clamp-2">Barang jastip pilihan terbaik dengan kualitas terjamin.</p>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-auto gap-4">
                                <span class="font-headline-md text-xl font-black text-secondary dark:text-secondary-fixed">
                                    Rp. <?= number_format((float)$product->getPrice(), 0, ',', '.') ?>
                                </span>
                                <a href="https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>?text=Halo%20Jastip%20Arunga,%20saya%20tertarik%20dengan%20<?= urlencode($product->getName()) ?>" target="_blank" 
                                   class="bg-primary text-white px-5 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-primary-container transition-colors shadow-md active:scale-95 whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[20px]" data-weight="fill">shopping_cart</span>
                                    <span class="font-label-md font-bold text-sm">Pesan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
