<?php
$csrfToken = App\Helper\CsrfHelper::getToken();
$cartError = $_SESSION['cart_error'] ?? null;
unset($_SESSION['cart_error']);
?>

<?php if ($success): ?>
<!-- ── SUCCESS STATE ─────────────────────────────────────────────── -->
<section class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full bg-surface rounded-3xl shadow-xl border border-outline-variant/20 p-8 text-center">
        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-green-600 text-[40px]">check_circle</span>
        </div>
        <h1 class="text-2xl font-bold text-on-surface mb-2">Pesanan Berhasil! &#127881;</h1>
        <p class="text-on-surface-variant mb-6">Pesananmu sudah kami terima. Simpan nomor order untuk melacak status.</p>

        <div class="bg-surface-container-low rounded-2xl p-5 mb-6">
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Nomor Order</p>
            <p class="text-2xl font-black text-primary tracking-widest" id="order-number-display">
                <?= htmlspecialchars($success['orderNumber']) ?>
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <button id="wa-confirm-btn"
                    class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md active:scale-95">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Konfirmasi via WhatsApp
            </button>
            <button id="ig-confirm-btn"
                    class="flex items-center justify-center gap-2 bg-gradient-to-br from-[#833AB4] via-[#E1306C] to-[#F77737] hover:opacity-90 text-white font-bold py-3.5 rounded-xl transition-all shadow-md active:scale-95">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                Konfirmasi via Instagram DM
            </button>
            <a href="?page=home"
               class="flex items-center justify-center gap-2 border-2 border-outline-variant text-on-surface font-bold py-3.5 rounded-xl hover:bg-surface-container transition-colors active:scale-95">
                <span class="material-symbols-outlined text-[20px]">storefront</span>
                Lanjut Belanja
            </a>
        </div>

        <p class="text-xs text-on-surface-variant mt-4">
            Bisa lacak pesanan di
            <a href="?page=home#lacak-pesanan" class="text-primary font-bold underline">halaman Lacak Pesanan</a>
            dengan nomor order di atas.
        </p>
    </div>
</section>
<script>
localStorage.removeItem('mbutitip_cart');
updateCartBadge();
window._ORDER    = <?= json_encode($success,  JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
window._ADMIN_WA = <?= json_encode($adminWa,  JSON_UNESCAPED_UNICODE) ?>;
window._ADMIN_IG = <?= json_encode($adminIg,  JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php else: ?>
<!-- ── CART STATE ─────────────────────────────────────────────────── -->
<section class="max-w-7xl mx-auto px-4 md:px-8 py-12">
    <h1 class="text-3xl font-bold text-primary mb-8 flex justify-center items-center gap-3 text-center">
        <span class="material-symbols-outlined text-[32px]">shopping_cart</span>
        Keranjang Belanja
    </h1>

    <?php if ($cartError): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm font-medium flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">warning</span>
        <?= htmlspecialchars($cartError) ?>
    </div>
    <?php endif; ?>

    <!-- Empty state -->
    <div id="cart-empty" class="hidden text-center py-24">
        <div class="text-7xl mb-6">&#128722;</div>
        <h2 class="text-2xl font-bold text-on-surface mb-3">Keranjang Kosong</h2>
        <p class="text-on-surface-variant mb-8">Belum ada produk yang ditambahkan.</p>
        <a href="?page=catalog" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3.5 rounded-xl font-bold hover:-translate-y-0.5 hover:shadow-lg transition-all active:scale-95">
            <span class="material-symbols-outlined">storefront</span> Lihat Katalog
        </a>
    </div>

    <!-- Cart content -->
    <div id="cart-content" class="hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left: Items -->
            <div class="lg:col-span-2 space-y-4">
                <div id="cart-items-list" class="space-y-3"></div>
                <button onclick="clearCart()"
                        class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1 mt-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">delete_sweep</span> Kosongkan Keranjang
                </button>
            </div>

            <!-- Right: Summary + Checkout Form -->
            <div class="space-y-6">
                <!-- Summary -->
                <div class="bg-surface rounded-2xl border border-outline-variant/30 p-6 shadow-sm">
                    <h2 class="font-bold text-lg text-on-surface mb-4">Ringkasan</h2>
                    <div class="flex justify-between text-on-surface-variant text-sm mb-2">
                        <span>Total item</span>
                        <span id="summary-qty">0 item</span>
                    </div>
                    <div class="border-t border-outline-variant/30 my-3"></div>
                    <div class="flex justify-between font-black text-primary text-lg">
                        <span>Total</span>
                        <span id="summary-total">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="bg-surface rounded-2xl border border-outline-variant/30 p-6 shadow-sm">
                    <h2 class="font-bold text-lg text-on-surface mb-5">Data Pemesan</h2>
                    <form method="POST" action="?page=cart&action=checkout" id="checkout-form" class="space-y-4" onsubmit="return injectCart()">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="list_item_order" id="list_item_order_input">
                        <input type="hidden" name="subTotal" id="sub_total_input">

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="namaPemesan" required placeholder="Nama kamu"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" name="whatsappPemesan" required placeholder="628xxxxxxxxxx"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Alamat Pengiriman</label>
                            <textarea name="alamatPemesan" rows="2" placeholder="Alamat lengkap"
                                      class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm focus:ring-2 focus:ring-primary/30 outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Username Instagram</label>
                            <input type="text" name="instagramUserNamePemesan" placeholder="@username"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        </div>

                        <div class="pt-2 border-t border-outline-variant/30">
                            <button type="submit"
                                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl active:scale-95 transition-all shadow-md flex items-center justify-center gap-2 text-base">
                                <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                                Buat Pesanan
                            </button>
                            <p class="text-xs text-on-surface-variant text-center mt-2">
                                Pesananmu akan tercatat dan bisa dilacak dengan nomor order.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<script src="js/cart-page.js"></script>
<script>
(function() {
    function buildMsg(bold) {
        var d = window._ORDER;
        if (!d) return '';
        var wave    = String.fromCodePoint(0x1F44B);
        var receipt = String.fromCodePoint(0x1F9FE);
        var box     = String.fromCodePoint(0x1F4E6);
        var money   = String.fromCodePoint(0x1F4B0);
        var person  = String.fromCodePoint(0x1F464);
        var phone   = String.fromCodePoint(0x1F4F1);
        var house   = String.fromCodePoint(0x1F3E0);
        var cam     = String.fromCodePoint(0x1F4F8);
        var pray    = String.fromCodePoint(0x1F64F);
        var fmt = function(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(Number(n))); };
        var items = '';
        (d.items || []).forEach(function(item) {
            var qty = item.qty || 1;
            var sub = fmt((item.price || 0) * qty);
            items += '• ' + item.name + ' x ' + qty + ' — ' + sub + '\n';
        });
        var b = bold ? '*' : '';
        var msg = 'Halo Mbu Titip Arunga! ' + wave + '\n\n'
            + 'Saya mau konfirmasi pesanan:\n\n'
            + receipt + ' ' + b + 'No. Order: ' + d.orderNumber + b + '\n\n'
            + box + ' ' + b + 'Pesanan:' + b + '\n' + items + '\n'
            + money + ' ' + b + 'Total: ' + d.total + b + '\n\n'
            + person + ' ' + b + 'Nama:' + b + ' ' + d.nama + '\n'
            + phone + ' ' + b + 'WA:' + b + ' ' + d.wa + '\n';
        if (d.alamat) msg += house + ' ' + b + 'Alamat:' + b + ' ' + d.alamat + '\n';
        if (!bold && d.ig) msg += cam + ' Instagram: @' + d.ig + '\n';
        msg += '\nMohon konfirmasi ketersediaan ya! Terima kasih ' + pray;
        return msg;
    }

    var waBtn = document.getElementById('wa-confirm-btn');
    if (waBtn) {
        waBtn.addEventListener('click', function() {
            location.href = 'https://wa.me/' + (window._ADMIN_WA || '') + '?text=' + encodeURIComponent(buildMsg(true));
        });
    }

    var btn = document.getElementById('ig-confirm-btn');
    if (!btn) return;

    btn.addEventListener('click', function() {
        var igMsg   = buildMsg(false);
        var adminIg = window._ADMIN_IG || '';

        try { navigator.clipboard.writeText(igMsg); } catch(e) {}

        location.href = 'https://ig.me/m/' + adminIg;

        btn.disabled = true;
        btn.style.background = '#16a34a';

        var sisa = 5;
        btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">check</span>&nbsp;Pesan disalin! Instagram sudah terbuka, paste pesannya. Tombol aktif lagi dalam <span id="ig-countdown">' + sisa + '</span> detik...';

        var interval = setInterval(function() {
            sisa--;
            var el = document.getElementById('ig-countdown');
            if (el) el.textContent = sisa;
            if (sisa <= 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.style.background = '';
                btn.innerHTML = '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg> Konfirmasi via Instagram DM';
            }
        }, 1000);
    });
})();
</script>
