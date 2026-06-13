<!-- Hero Section for Catalog -->
<section class="relative overflow-hidden pt-20 sm:pt-28 pb-10 sm:pb-16 px-4 md:px-margin-desktop bg-surface-container-low rounded-b-[3rem] shadow-sm mb-10">
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
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                <?php foreach($products as $product): ?>
                    <!-- Product Card -->
                    <div class="group bg-surface rounded-2xl overflow-hidden shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-outline-variant/20 flex flex-col">
                        <div class="relative h-36 sm:h-52 overflow-hidden bg-surface-container-high flex items-center justify-center flex-shrink-0 cursor-pointer" onclick="openProductModal(<?= $product->getId() ?>)">
                            <?php if ($product->getImageUrl()): ?>
                                <img src="<?= htmlspecialchars($product->getImageUrl()) ?>" alt="<?= htmlspecialchars($product->getName()) ?>" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            <?php else: ?>
                                <span class="text-4xl sm:text-6xl">📦</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 sm:p-5 flex flex-col flex-1">
                            <h3 class="font-bold text-sm sm:text-base text-on-surface mb-1 line-clamp-2 cursor-pointer hover:text-primary transition-colors" title="<?= htmlspecialchars($product->getName()) ?>" onclick="openProductModal(<?= $product->getId() ?>)">
                                <?= htmlspecialchars($product->getName()) ?>
                            </h3>
                            <div class="mt-auto pt-2 flex flex-col gap-2">
                                <span class="font-black text-sm sm:text-base text-secondary dark:text-secondary-fixed">
                                    Rp <?= number_format((float)$product->getPrice(), 0, ',', '.') ?>
                                </span>
                                <div class="flex flex-col gap-1.5">
                                    <button onclick="openProductModal(<?= $product->getId() ?>)"
                                       class="bg-primary text-white py-2 sm:py-2.5 rounded-xl flex items-center justify-center gap-1 hover:bg-primary-container transition-colors shadow-sm active:scale-95">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        <span class="font-bold text-xs">Detail</span>
                                    </button>
                                    <button onclick="addGridToCart(event, <?= $product->getId() ?>, this)"
                                       class="bg-secondary-container text-on-secondary-container py-2 sm:py-2.5 rounded-xl flex items-center justify-center gap-1 hover:bg-secondary hover:text-white transition-colors shadow-sm active:scale-95 border border-transparent">
                                        <span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
                                        <span class="font-bold text-xs">Keranjang</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination Controls -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="flex justify-center items-center gap-2 mt-12">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=catalog&p=<?= $currentPage - 1 ?>" class="p-3 bg-surface-container-low hover:bg-outline-variant/30 text-on-surface rounded-xl transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    <?php else: ?>
                        <div class="p-3 bg-surface-container-low text-on-surface-variant opacity-50 cursor-not-allowed rounded-xl">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="px-4 py-2 bg-primary/10 text-primary font-bold rounded-xl">
                        Halaman <?= $currentPage ?> dari <?= $totalPages ?>
                    </div>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=catalog&p=<?= $currentPage + 1 ?>" class="p-3 bg-surface-container-low hover:bg-outline-variant/30 text-on-surface rounded-xl transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    <?php else: ?>
                        <div class="p-3 bg-surface-container-low text-on-surface-variant opacity-50 cursor-not-allowed rounded-xl">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Product Detail Modal -->
<div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-surface w-full max-w-2xl max-h-[92vh] rounded-3xl shadow-2xl flex flex-col mx-4 my-4 overflow-hidden transform scale-95 transition-transform duration-300" id="product-modal-content">

        <!-- Header -->
        <div class="flex-shrink-0 flex items-center justify-between px-4 py-3 border-b border-outline-variant/30">
            <h3 class="font-bold text-base text-on-surface">Detail Produk</h3>
            <button onclick="closeProductModal()" class="w-9 h-9 rounded-full hover:bg-surface-container-high flex items-center justify-center text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="p-4 overflow-y-auto flex-1">
            <div class="flex flex-row gap-4">
                <!-- Product Image — fixed size via inline style -->
                <div id="modal-img-wrap" style="width:120px;min-width:120px;height:120px;" class="flex-shrink-0">
                    <div class="w-full h-full bg-surface-container-high rounded-xl flex items-center justify-center overflow-hidden border border-outline-variant/20">
                        <img id="modal-img" src="" alt="" class="w-full h-full object-contain p-1">
                        <div id="modal-no-img" class="hidden flex-col items-center justify-center text-outline-variant">
                            <span class="material-symbols-outlined" style="font-size:2rem">image</span>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex-1 min-w-0 flex flex-col">
                    <h2 id="modal-title" class="font-bold text-sm text-on-surface mb-1" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden"></h2>
                    <div id="modal-price" class="font-black text-secondary mb-2" style="font-size:1rem"></div>
                    <div id="modal-stock-container" class="text-on-surface-variant mb-2" style="font-size:0.75rem">
                        Stok: <span id="modal-stock" class="font-bold text-on-surface"></span>
                    </div>
                    <div id="modal-variants-container" class="space-y-3"></div>
                </div>
            </div>
        </div>

        <!-- Footer (Sticky Button) -->
        <div class="flex-shrink-0 p-3 sm:p-4 border-t border-outline-variant/30 bg-surface">
            <button id="modal-cart-btn" onclick="addCurrentToCart()" class="w-full bg-primary text-white py-3.5 rounded-xl flex items-center justify-center gap-2 font-bold hover:bg-primary/90 transition-colors shadow-md active:scale-95">
                <span class="material-symbols-outlined" data-weight="fill">add_shopping_cart</span>
                Masuk Keranjang
            </button>
        </div>
    </div>
</div>

<?php
// Prepare products array for JS
$jsProducts = [];
if (!empty($products)) {
    foreach ($products as $p) {
        $jsProducts[$p->getId()] = [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'price' => $p->getPrice(),
            'imageUrl' => $p->getImageUrl(),
            'description' => $p->getDescription()
        ];
    }
}
?>

<script>
    const productsData = <?= json_encode($jsProducts) ?>;
    const variantsData = <?= isset($variantsData) ? json_encode($variantsData) : '{}' ?>;
    const adminWa = "<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>";
    
    // Format currency
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }
    
    // Modal State
    let currentProductId = null;
    let selectedOptions = [];
    
    function openProductModal(productId) {
        const product = productsData[productId];
        if (!product) return;
        
        currentProductId = productId;
        const vData = variantsData[productId];
        
        document.getElementById('modal-title').textContent = product.name;
        
        // Setup initial image
        const imgEl = document.getElementById('modal-img');
        const noImgEl = document.getElementById('modal-no-img');
        if (product.imageUrl) {
            imgEl.src = product.imageUrl;
            imgEl.classList.remove('hidden');
            noImgEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
        }
        
        // Check if has variants
        const varContainer = document.getElementById('modal-variants-container');
        varContainer.innerHTML = '';
        selectedOptions = [];
        
        if (vData && vData.groups.length > 0) {
            // Render groups
            vData.groups.forEach((group, groupIdx) => {
                const groupDiv = document.createElement('div');
                
                const label = document.createElement('p');
                label.className = 'font-bold text-sm text-on-surface mb-2';
                label.textContent = group.name;
                groupDiv.appendChild(label);
                
                const btnContainer = document.createElement('div');
                btnContainer.className = 'flex flex-wrap gap-2';
                
                group.options.forEach((opt) => {
                    const btn = document.createElement('button');
                    btn.className = 'variant-opt-btn px-4 py-2 border border-outline-variant/50 rounded-lg text-sm font-medium hover:bg-primary/5 hover:border-primary transition-colors text-on-surface';
                    btn.textContent = opt;
                    btn.onclick = () => selectVariantOption(groupIdx, opt, btn, btnContainer);
                    btnContainer.appendChild(btn);
                });
                
                groupDiv.appendChild(btnContainer);
                varContainer.appendChild(groupDiv);
            });
            
            // Initial reset of price/stock based on raw product
            document.getElementById('modal-price').textContent = formatRupiah(product.price);
            document.getElementById('modal-stock').textContent = 'Pilih Variasi';
            updateWaButton();
        } else {
            // No variants
            document.getElementById('modal-price').textContent = formatRupiah(product.price);
            document.getElementById('modal-stock').textContent = 'Tersedia'; // We don't track base product stock currently, assume available
            updateWaButton();
        }
        
        // Show modal
        const modal = document.getElementById('product-modal');
        const content = document.getElementById('product-modal-content');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    function selectVariantOption(groupIdx, opt, btnEl, containerEl) {
        // Deselect others in group
        containerEl.querySelectorAll('.variant-opt-btn').forEach(b => {
            b.classList.remove('bg-primary/10', 'border-primary', 'text-primary');
            b.classList.add('border-outline-variant/50', 'text-on-surface');
        });
        
        // Select this one
        btnEl.classList.remove('border-outline-variant/50', 'text-on-surface');
        btnEl.classList.add('bg-primary/10', 'border-primary', 'text-primary');
        
        selectedOptions[groupIdx] = opt;
        
        checkVariantMatch();
    }
    
    function checkVariantMatch() {
        if (!currentProductId) return;
        const vData = variantsData[currentProductId];
        if (!vData || vData.groups.length === 0) return;
        
        // Are all groups selected?
        if (selectedOptions.length === vData.groups.length && !selectedOptions.includes(undefined)) {
            const comboString = selectedOptions.join(' - ');
            const matchedVariant = vData.variants.find(v => v.name === comboString);
            
            if (matchedVariant) {
                document.getElementById('modal-price').textContent = formatRupiah(matchedVariant.price);
                document.getElementById('modal-stock').textContent = matchedVariant.stock;
                
                // Update image if any
                if (matchedVariant.image_id) {
                    const img = vData.images.find(i => i.id === matchedVariant.image_id);
                    if (img) {
                        const imgEl = document.getElementById('modal-img');
                        imgEl.src = img.url;
                        imgEl.classList.remove('hidden');
                        document.getElementById('modal-no-img').classList.add('hidden');
                    }
                }
                
                updateWaButton(matchedVariant);
            }
        }
    }
    
    function updateWaButton(variant = null) {
        const btn = document.getElementById('modal-wa-btn');
        const product = productsData[currentProductId];
        
        // Reset classes
        if (btn) {
            btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            btn.removeAttribute('href');
        }
        
        const cartBtn = document.getElementById('modal-cart-btn');
        cartBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        
        let text = `Halo Jastip Arunga, saya tertarik dengan ${product.name}`;
        if (variant) {
            text += ` variasi ${variant.name}`;
            if (variant.stock <= 0) {
                if (btn) {
                    btn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">chat</span> Stok Habis`;
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                
                cartBtn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">add_shopping_cart</span> Stok Habis`;
                cartBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                return;
            }
        } else {
            // Require variant selection if variants exist
            const vData = variantsData[currentProductId];
            if (vData && vData.groups.length > 0) {
                if (btn) {
                    btn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">chat</span> Pilih Variasi`;
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                
                cartBtn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">add_shopping_cart</span> Pilih Variasi`;
                cartBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                return;
            }
        }
        
        const waText = encodeURIComponent(text);
        if (btn) {
            btn.href = `https://wa.me/${adminWa}?text=${waText}`;
            btn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">chat</span> Pesan via WA`;
        }
        
        cartBtn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">add_shopping_cart</span> Masuk Keranjang`;
    }
    
    function addGridToCart(event, productId, btnElement) {
        event.stopPropagation();
        const vData = variantsData[productId];
        if (vData && vData.groups.length > 0) {
            openProductModal(productId);
            return;
        }
        
        const product = productsData[productId];
        const cart = getCart();
        const exist = cart.find(i => i.id === product.id);
        if (exist) exist.qty++;
        else cart.push({ id: product.id, name: product.name, price: product.price, imageUrl: product.imageUrl, qty: 1 });
        saveCart(cart);
        
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = `<span class="material-symbols-outlined text-[18px]" data-weight="fill">check</span> <span class="font-label-md font-bold text-xs">Selesai</span>`;
        btnElement.classList.add('bg-green-500', 'text-white', 'border-green-600');
        btnElement.classList.remove('bg-secondary-container', 'text-on-secondary-container', 'border-transparent');
        setTimeout(() => {
            btnElement.innerHTML = originalText;
            btnElement.classList.remove('bg-green-500', 'text-white', 'border-green-600');
            btnElement.classList.add('bg-secondary-container', 'text-on-secondary-container', 'border-transparent');
        }, 1500);
    }
    
    function addCurrentToCart() {
        const product = productsData[currentProductId];
        const vData = variantsData[currentProductId];
        
        let cartItem = {
            id: product.id,
            name: product.name,
            price: product.price,
            imageUrl: product.imageUrl,
            qty: 1
        };
        
        if (vData && vData.groups.length > 0) {
            if (selectedOptions.length !== vData.groups.length || selectedOptions.includes(undefined)) {
                showAlert('Pilih variasi terlebih dahulu!', 'warning');
                return;
            }
            const comboString = selectedOptions.join(' - ');
            const matchedVariant = vData.variants.find(v => v.name === comboString);
            if (!matchedVariant) return;
            if (matchedVariant.stock <= 0) return;
            
            cartItem.id = `${product.id}-${matchedVariant.id}`;
            cartItem.name = `${product.name} (${matchedVariant.name})`;
            cartItem.price = matchedVariant.price;
            
            if (matchedVariant.image_id) {
                const img = vData.images.find(i => i.id === matchedVariant.image_id);
                if (img) cartItem.imageUrl = img.url;
            }
        }
        
        const cart = getCart();
        const exist = cart.find(i => i.id === cartItem.id);
        if (exist) exist.qty++;
        else cart.push(cartItem);
        saveCart(cart);
        
        const btn = document.getElementById('modal-cart-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = `<span class="material-symbols-outlined" data-weight="fill">check</span> Berhasil`;
        btn.classList.add('bg-green-500', 'text-white', 'border-green-600');
        btn.classList.remove('bg-secondary-container', 'text-on-secondary-container', 'border-transparent');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-500', 'text-white', 'border-green-600');
            btn.classList.add('bg-secondary-container', 'text-on-secondary-container', 'border-transparent');
        }, 1500);
    }
    
    function closeProductModal() {
        const modal = document.getElementById('product-modal');
        const content = document.getElementById('product-modal-content');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        document.body.style.overflow = '';
        currentProductId = null;
    }
    
    // Close modal on click outside
    document.getElementById('product-modal').addEventListener('click', (e) => {
        if (e.target.id === 'product-modal') closeProductModal();
    });

    // Responsive image size
    function updateModalImgSize() {
        const wrap = document.getElementById('modal-img-wrap');
        if (!wrap) return;
        const w = window.innerWidth;
        const size = w < 640 ? '120px' : w < 1024 ? '180px' : '220px';
        wrap.style.width  = size;
        wrap.style.minWidth = size;
        wrap.style.height = size;
    }
    window.addEventListener('resize', updateModalImgSize);
    // Auto-open modal from URL param ?open=<id>
    (function() {
        const params = new URLSearchParams(location.search);
        const openId = parseInt(params.get('open'));
        if (openId) openProductModal(openId);
    })();
</script>
