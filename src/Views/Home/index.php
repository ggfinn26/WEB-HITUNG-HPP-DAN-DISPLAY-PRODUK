<!-- Hero Section -->
<section class="relative overflow-hidden min-h-[90vh] flex items-center px-4 md:px-margin-desktop py-16">
    <!-- Background Decorative Elements -->
    <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-primary-container/5 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-[5%] left-[-10%] w-[400px] h-[400px] bg-secondary-container/10 rounded-full blur-3xl -z-10"></div>
    
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full">
        <div id="hero-content">
            <img alt="Arunga Kids Friendly Logo" class="h-36 w-auto mb-8 hero-logo rounded-2xl shadow-sm" src="favicon.png" />
            <h1 class="font-display-lg text-4xl md:text-5xl lg:text-display-lg text-secondary-container mb-4 hero-headline font-bold">Mbu Titip by Arunga Arungi Dunia</h1>
            <p class="font-headline-md text-xl md:text-headline-md text-on-surface-variant mb-10 hero-tagline">
                Temanmu Mengarungi Nusantara. Jasa Titip Terpercaya Seluruh Indonesia. Temukan barang-barang favorit dari berbagai daerah untuk keluarga tersayang.
            </p>
            <div class="flex flex-wrap gap-4 hero-cta">
                <a href="#katalog" class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-xl font-headline-md font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all active:scale-95 inline-block text-center">
                    Mulai Belanja
                </a>
                <a href="#lacak-pesanan" class="border-2 border-primary text-primary px-10 py-4 rounded-xl font-headline-md font-bold hover:bg-primary hover:text-white transition-all active:scale-95 inline-block text-center">
                    Lacak Pesanan
                </a>
            </div>
        </div>
<style>
.map-fullscreen {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 90 !important;
    aspect-ratio: auto !important;
    background: rgba(0, 0, 0, 0.5) !important;
    backdrop-filter: blur(4px) !important;
    max-width: none !important;
    border-radius: 0 !important;
    padding: 2rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.map-fullscreen .glass-panel {
    width: 100% !important;
    height: 100% !important;
    max-width: 1200px !important;
    max-height: 85vh !important;
    background: #ffffff !important;
    border-radius: 1.5rem !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    padding: 0 !important;
    border: none !important;
    overflow: hidden !important;
}
button.px-10 {
    background-color: #001E40 !important;
    color: #FFFFFF !important;
}
</style>

        <!-- Map Pinpoint Modal -->
        <div id="pinpoint-modal" class="fixed inset-0 z-[100] bg-black/50 hidden items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
            <div class="bg-surface rounded-3xl shadow-2xl p-6 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300" id="pinpoint-modal-content">
                <div class="flex justify-between items-center mb-4 border-b border-outline-variant/30 pb-4">
                    <h3 class="font-display-sm text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">location_on</span>
                        Produk di Wilayah Ini
                    </h3>
                    <button id="close-modal" class="text-on-surface-variant hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div id="modal-product-list" class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    <!-- Product items injected by JS -->
                </div>
            </div>
        </div>

        <div class="relative w-full aspect-[4/3] flex items-center justify-center hidden md:flex" id="hero-visual">
            <div class="relative w-full h-full max-w-2xl mx-auto glass-panel rounded-[2rem] p-6 shadow-xl border border-outline-variant/20 flex items-center justify-center bg-surface-container-low">
                <!-- TopoJSON Map Container -->
                <div class="relative w-full h-full text-primary-fixed-dim" id="map-container">
                    <button id="close-fullscreen-map" class="hidden absolute top-4 right-4 bg-surface p-3 rounded-full shadow-lg border border-outline-variant/30 z-[100] text-on-surface-variant hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                    <div class="d3-tooltip" id="d3-tooltip"></div>
                    <svg class="w-full h-full drop-shadow-md cursor-pointer" id="indonesia-map" preserveaspectratio="xMidYMid meet"></svg>
                    <!-- Floating Badge -->
                    <div class="absolute bottom-4 left-4 bg-surface p-3 rounded-2xl shadow-md flex items-center gap-2 border border-outline-variant/30 z-30">
                        <span class="w-3 h-3 rounded-full bg-secondary-container animate-pulse"></span>
                        <span class="font-label-md text-sm font-bold text-primary">Jaringan Nusantara</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Catalog Section -->
<section id="katalog" class="py-24 bg-surface-container-low rounded-[3rem] my-10 mx-4 md:mx-0">
    <div class="max-w-7xl mx-auto px-6 md:px-margin-desktop">
        
        <div class="text-center max-w-2xl mx-auto mb-16 px-4">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary font-bold text-sm mb-6">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Jastip Arunga
            </div>
            <h2 class="font-display-lg text-4xl font-bold text-primary mb-4">Katalog Produk</h2>
            <p class="text-on-surface-variant text-lg">Pilih produk dan jajanan favorit keluarga dari seluruh pelosok Indonesia. Dikirim langsung dengan aman dan cepat.</p>
        </div>
        
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
                            
                            <div class="absolute top-4 left-4 bg-surface px-4 py-1.5 rounded-full text-xs font-bold text-primary flex items-center gap-1 shadow-sm backdrop-blur-sm location-pill"
                                 data-lat="<?= htmlspecialchars($product->getLatitude()) ?>" 
                                 data-lon="<?= htmlspecialchars($product->getLongitude()) ?>">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> <span class="location-text">INDONESIA</span>
                            </div>
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

        <?php if (!empty($products) && count($products) >= 6): ?>
        <div class="mt-16 text-center">
            <a href="?page=catalog" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 rounded-xl font-headline-md font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 hover:bg-primary-container transition-all active:scale-95">
                Lihat Selengkapnya
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Interactive Order Tracking Section -->
<section id="lacak-pesanan" class="relative py-32 px-4 md:px-margin-desktop overflow-hidden mt-10">
    <!-- Background Image / Color -->
    <div class="absolute inset-0 -z-20 bg-primary">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-surface via-transparent to-transparent"></div>
    </div>
    
    <div class="max-w-3xl mx-auto text-center">
        <div class="glass-panel p-10 md:p-16 rounded-[3rem] shadow-2xl relative overflow-hidden">
            <h2 class="font-display-lg text-4xl font-bold text-primary mb-6">Lacak Pesanan Anda</h2>
            <p class="font-body-lg text-lg text-on-surface-variant mb-10">Masukkan Order ID untuk melihat status perjalanan paket impianmu melintasi Nusantara.</p>
            
            <form action="?page=track" method="POST" class="flex flex-col md:flex-row gap-4 mb-4 relative z-10">
                <div class="flex-grow relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">search</span>
                    <input name="orderNumber" required class="w-full pl-14 pr-6 py-5 rounded-2xl border-2 border-outline-variant focus:border-secondary-container focus:ring-4 focus:ring-secondary-container/20 bg-surface outline-none transition-all font-body-md text-lg font-medium text-on-surface" placeholder="Contoh: ORD-20260530-1234" type="text"/>
                </div>
                <button type="submit" class="bg-primary text-white px-10 py-5 rounded-2xl font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-3 text-lg shadow-lg active:scale-95">
                    <span>Lacak</span>
                    <span class="material-symbols-outlined">explore</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Custom Request Section -->
<section class="py-24 px-4 md:px-margin-desktop mb-10 relative">
    <div class="max-w-4xl mx-auto glass-panel border border-outline-variant/30 p-10 md:p-16 rounded-[3rem] shadow-xl relative overflow-hidden bg-surface">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-secondary-container/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-primary-container/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="text-center space-y-4 mb-12 relative z-10">
            <span class="inline-block px-5 py-2 rounded-full bg-secondary-container/10 text-secondary text-sm font-bold tracking-widest uppercase mb-2">Layanan Khusus</span>
            <h2 class="text-4xl font-bold text-primary">Tidak Menemukan Barangmu?</h2>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto mt-4">
                Jangan khawatir! Beritahu kami barang apa yang ingin kamu titip, dan kami akan segera mencarikannya untukmu di seluruh belahan dunia.
            </p>
        </div>

        <form id="customRequestForm" class="relative z-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" id="reqName" required placeholder="John Doe"
                           class="w-full px-6 py-4 rounded-2xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm text-on-surface font-medium">
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Nama / Link Barang</label>
                    <input type="text" id="reqItem" required placeholder="Misal: Sepatu Nike Air Max Size 42"
                           class="w-full px-6 py-4 rounded-2xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm text-on-surface font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Jumlah (Qty)</label>
                    <input type="number" id="reqQty" aria-label="Jumlah Pesanan" required min="1" value="1"
                           class="w-full px-6 py-4 rounded-2xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm text-on-surface font-medium">
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Estimasi Harga (Opsional)</label>
                    <input type="text" id="reqPrice" placeholder="Misal: Rp. 1.500.000"
                           class="w-full px-6 py-4 rounded-2xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm text-on-surface font-medium">
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Catatan Tambahan (Opsional)</label>
                <textarea id="reqNote" rows="3" placeholder="Warna spesifik, ukuran, atau detail lainnya..."
                          class="w-full px-6 py-4 rounded-2xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm text-on-surface font-medium resize-none"></textarea>
            </div>

            <div class="text-center pt-6">
                <button type="submit" class="px-10 py-5 bg-[#075E54] text-white font-bold text-lg rounded-2xl hover:bg-[#128C7E] transform hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-[#075E54]/30 flex items-center justify-center mx-auto gap-3 w-full sm:w-auto">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.811.922 3.148.922 3.18 0 5.767-2.587 5.768-5.766 0-3.181-2.587-5.764-5.767-5.764zM12.031 16.442c-1.042 0-1.748-.306-2.553-.787l-.183-.111-1.318.347.353-1.286-.122-.194c-.544-.863-.829-1.63-.829-2.478.001-2.613 2.13-4.743 4.745-4.743 2.614 0 4.745 2.13 4.745 4.743 0 2.613-2.131 4.743-4.745 4.743z"></path><path d="M14.659 13.987c-.144-.072-.852-.42-.984-.468-.131-.048-.227-.072-.323.072-.096.144-.372.468-.456.564-.084.096-.168.108-.312.036-.144-.072-.609-.225-1.161-.716-.429-.382-.72-.853-.804-1.002-.084-.144-.009-.222.063-.294.065-.065.144-.168.216-.252.072-.084.096-.144.144-.24.048-.096.024-.18-.012-.252-.036-.072-.323-.779-.442-1.066-.117-.281-.235-.243-.323-.248-.084-.004-.18-.005-.276-.005-.096 0-.252.036-.384.18-.132.144-.504.492-.504 1.2s.516 1.392.588 1.488c.072.096 1.014 1.547 2.455 2.17.344.149.613.238.823.305.346.11.66.094.908.057.279-.041.852-.348.972-.684.12-.336.12-.624.084-.684-.036-.06-.132-.096-.276-.168z"></path></svg>
                    Pesan via WhatsApp Sekarang
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Script for Map and Custom Form -->
<script>
    // WhatsApp Redirect Logic
    document.getElementById('customRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('reqName').value;
        const item = document.getElementById('reqItem').value;
        const qty = document.getElementById('reqQty').value;
        const price = document.getElementById('reqPrice').value;
        const note = document.getElementById('reqNote').value;
        
        let message = `Halo Jastip Arunga, saya *${name}* ingin request barang di luar katalog:\n\n`;
        message += `- *Nama Barang:* ${item}\n`;
        message += `- *Jumlah:* ${qty}\n`;
        if (price) message += `- *Estimasi Harga:* ${price}\n`;
        if (note) message += `- *Catatan Tambahan:* ${note}\n`;
        message += `\nMohon informasi cara pembayaran dan konfirmasinya. Terima kasih!`;
        
        const waUrl = `https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
        
        this.reset();
    });

    // --- Reverse Geocoding for Product Location Pills ---
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.location-pill').forEach(pill => {
            const lat = pill.dataset.lat;
            const lon = pill.dataset.lon;
            if (lat && lon) {
                fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=id`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && (data.city || data.principalSubdivision)) {
                            const loc = data.city || data.principalSubdivision;
                            pill.querySelector('.location-text').textContent = loc.toUpperCase();
                        }
                    })
                    .catch(e => console.error("Geocoding error:", e));
            }
        });
    });

    // D3 Map Initialization
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof d3 !== 'undefined' && document.getElementById("indonesia-map")) {
            const container = d3.select("#map-container");
            const svg = d3.select("#indonesia-map");
            const tooltip = d3.select("#d3-tooltip");

            const containerNode = container.node();
            const width = containerNode.clientWidth || 600;
            const height = containerNode.clientHeight || 400;

            svg.attr("viewBox", `0 0 ${width} ${height}`);

            const g = svg.append("g");

            const projection = d3.geoMercator()
                .center([118, -2.5])
                .scale(width * 1.1)
                .translate([width / 2, height / 2]);

            const path = d3.geoPath().projection(projection);

            const zoom = d3.zoom()
                .scaleExtent([1, 8])
                .translateExtent([[0, 0], [width, height]])
                .on("zoom", (event) => {
                    g.attr("transform", event.transform);
                });

            svg.call(zoom);

            const heroVisual = document.getElementById('hero-visual');
            const closeMapBtn = document.getElementById('close-fullscreen-map');

            svg.on("click", function(event) {
                if (heroVisual.classList.contains('map-fullscreen')) return;
                heroVisual.classList.add('map-fullscreen');
                closeMapBtn.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeMapBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                heroVisual.classList.remove('map-fullscreen');
                closeMapBtn.classList.add('hidden');
                document.body.style.overflow = '';
                
                svg.transition().duration(750).call(
                    zoom.transform,
                    d3.zoomIdentity
                );
            });

            d3.json("https://raw.githubusercontent.com/denyherianto/indonesia-geojson-topojson-maps-with-38-provinces/main/TopoJSON/indonesia-38-provinces.topo.json").then(function(topology) {
                g.selectAll("path")
                    .data(topojson.feature(topology, topology.objects["indonesia-38-prov-topo"]).features)
                    .enter().append("path")
                    .attr("class", "province-path")
                    .attr("d", path)
                    .style("cursor", "pointer")
                    .on("mouseover", function(event, d) {
                        d3.select(this).style("fill", "#799dd6");
                        tooltip.transition()
                            .duration(200)
                            .style("opacity", 1);
                        tooltip.html(d.properties.PROVINSI || "Provinsi")
                            .style("left", (event.pageX + 10) + "px")
                            .style("top", (event.pageY - 28) + "px");
                    })
                    .on("mouseout", function(d) {
                        d3.select(this).style("fill", ""); 
                        tooltip.transition()
                            .duration(500)
                            .style("opacity", 0);
                    });

                // Add dynamic pinpoints for live products
                const escHtml = s => { const d = document.createElement('div'); d.textContent = s ?? ''; return d.innerHTML; };
                const rawProducts = <?= $productsJson ?? '[]' ?>;
                const mapProducts = rawProducts.filter(p => p.latitude !== null && p.longitude !== null);

                // Group products by coordinates
                const productsByLocation = {};
                mapProducts.forEach(p => {
                    const key = `${p.latitude},${p.longitude}`;
                    if (!productsByLocation[key]) {
                        productsByLocation[key] = [];
                    }
                    productsByLocation[key].push(p);
                });
                const groupedMapProducts = Object.values(productsByLocation);

                g.selectAll(".city-marker")
                    .data(groupedMapProducts)
                    .enter().append("circle")
                    .attr("class", "city-marker cursor-pointer")
                    .attr("cx", d => projection([d[0].longitude, d[0].latitude])[0])
                    .attr("cy", d => projection([d[0].longitude, d[0].latitude])[1])
                    .attr("r", 5)
                    .style("fill", "#F59E0B")
                    .style("stroke", "#ffffff")
                    .style("stroke-width", 1.5)
                    .on("mouseover", function(event, d) {
                        d3.select(this)
                            .transition()
                            .duration(200)
                            .attr("r", 8)
                            .style("fill", "#EF4444");
                        
                        tooltip.transition()
                            .duration(200)
                            .style("opacity", 1);
                            
                        const totalProducts = d.length;
                        const pName = totalProducts > 1 ? `${totalProducts} Produk Tersedia` : d[0].name;
                        
                        const miniCatalogHtml = `
                            <div class="bg-surface p-3 rounded-xl shadow-lg border border-outline-variant/30 w-48 font-sans">
                                <div class="bg-surface-container-high h-24 rounded-lg flex items-center justify-center mb-2 overflow-hidden">
                                    ${d[0].imageUrl ? `<img src="${escHtml(d[0].imageUrl)}" class="w-full h-full object-cover">` : `<span class="text-3xl">📦</span>`}
                                </div>
                                <h4 class="font-bold text-sm text-on-surface line-clamp-1 mb-1">${escHtml(pName)}</h4>
                                <p class="text-xs text-on-surface-variant italic">Klik untuk melihat detail</p>
                            </div>
                        `;
                        
                        tooltip.html(miniCatalogHtml)
                            .style("left", (event.pageX + 15) + "px")
                            .style("top", (event.pageY - 40) + "px")
                            .style("background", "transparent")
                            .style("box-shadow", "none")
                            .style("padding", "0")
                            .style("border", "none");
                    })
                    .on("mouseout", function(event, d) {
                        d3.select(this)
                            .transition()
                            .duration(200)
                            .attr("r", 5)
                            .style("fill", "#F59E0B");
                        tooltip.transition()
                            .duration(500)
                            .style("opacity", 0);
                        
                        // Reset tooltip styles for regular province hover
                        setTimeout(() => {
                            tooltip.style("background", "rgba(0, 0, 0, 0.8)")
                                .style("color", "#fff")
                                .style("padding", "5px 10px")
                                .style("border-radius", "4px");
                        }, 500);
                    })
                    .on("click", function(event, d) {
                        event.stopPropagation();
                        // Zoom into the marker
                        svg.transition().duration(750).call(
                            zoom.transform,
                            d3.zoomIdentity
                                .translate(width / 2, height / 2)
                                .scale(6)
                                .translate(-projection([d[0].longitude, d[0].latitude])[0], -projection([d[0].longitude, d[0].latitude])[1])
                        );

                        // Show Modal
                        const modal = document.getElementById('pinpoint-modal');
                        const list = document.getElementById('modal-product-list');
                        
                        list.innerHTML = d.map(p => {
                            const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p.price);
                            return `
                                <div class="flex items-center gap-4 p-3 rounded-2xl bg-surface-container-low hover:bg-surface-container transition-colors border border-outline-variant/20">
                                    <div class="w-16 h-16 rounded-xl bg-surface-container-high flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        ${p.imageUrl ? `<img src="${escHtml(p.imageUrl)}" class="w-full h-full object-contain p-1">` : `<span class="text-2xl">📦</span>`}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-on-surface text-sm line-clamp-2">${escHtml(p.name)}</h4>
                                        <p class="text-secondary-container font-black text-sm mt-1">${priceFormatted}</p>
                                    </div>
                                    <a href="https://wa.me/<?= htmlspecialchars($_ENV['ADMIN_WA'] ?? '62895380123352') ?>?text=Halo%20Jastip%20Arunga,%20saya%20tertarik%20dengan%20${encodeURIComponent(p.name)}" target="_blank" class="bg-primary text-white p-2 rounded-full hover:bg-primary-container transition-colors shadow-sm active:scale-95">
                                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                                    </a>
                                </div>
                            `;
                        }).join('');
                        
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        void modal.offsetWidth; // trigger reflow
                        modal.classList.remove('opacity-0');
                        document.getElementById('pinpoint-modal-content').classList.remove('scale-95');
                        
                        // hide tooltip
                        tooltip.style("opacity", 0);
                    });

                // Modal Close Logic
                document.getElementById('close-modal').addEventListener('click', closeProductModal);
                document.getElementById('pinpoint-modal').addEventListener('click', function(e) {
                    if (e.target === this) closeProductModal();
                });

                function closeProductModal() {
                    const modal = document.getElementById('pinpoint-modal');
                    modal.classList.add('opacity-0');
                    document.getElementById('pinpoint-modal-content').classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 300);
                }

            }).catch(err => console.log("Map load error:", err));
        }
        
        // Simple GSAP Animation for Hero
        if(typeof gsap !== 'undefined') {
            gsap.from(".hero-headline", { y: 30, opacity: 0, duration: 1, delay: 0.2 });
            gsap.from(".hero-tagline", { y: 20, opacity: 0, duration: 1, delay: 0.4 });
            gsap.from(".hero-cta", { y: 20, opacity: 0, duration: 1, delay: 0.6 });
            gsap.from(".hero-logo", { scale: 0.8, opacity: 0, duration: 1, ease: "back.out(1.7)" });
        }
    });
</script>
