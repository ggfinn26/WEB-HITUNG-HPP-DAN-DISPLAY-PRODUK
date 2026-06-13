<?php
$existingItems = json_decode($order->getListItemOrder(), true) ?? [];
?>
<div class="glass p-8 md:p-12 rounded-3xl shadow-2xl max-w-3xl mx-auto border-t-4 border-t-primary relative overflow-hidden">
    <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-secondary opacity-5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="mb-8 flex items-center gap-4 relative z-10">
        <a href="?page=orders&action=show&id=<?= urlencode($order->getOrderNumber()) ?>"
           class="p-3 rounded-2xl bg-surface border border-outline-variant text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <span class="inline-block px-3 py-1 mb-1 rounded-full bg-primary/10 text-primary text-xs font-bold tracking-wider uppercase">Edit Order</span>
            <h1 class="text-2xl font-black text-text tracking-tight"><?= htmlspecialchars($order->getOrderNumber()) ?></h1>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 relative z-10">
            <?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form action="?page=orders&action=updateDetail&id=<?= urlencode($order->getOrderNumber()) ?>" method="POST" class="space-y-6 relative z-10">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2 group">
                <label class="block text-sm font-semibold text-text">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="namaPemesan" required
                           value="<?= htmlspecialchars($order->getNamaPemesan()) ?>"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all shadow-sm">
                </div>
            </div>

            <div class="space-y-2 group">
                <label class="block text-sm font-semibold text-text">No. WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <input type="text" name="whatsappPemesan" required
                           value="<?= htmlspecialchars($order->getWhatsappPemesan()) ?>"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-outline-variant bg-surface focus:ring-2 focus:ring-secondary focus:border-secondary outline-none transition-all shadow-sm">
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-text">Username Instagram <span class="text-muted font-normal">(Opsional)</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted font-bold">@</div>
                <input type="text" name="instagramUserNamePemesan"
                       value="<?= htmlspecialchars($order->getInstagramUserNamePemesan()) ?>"
                       class="w-full pl-10 pr-4 py-3 rounded-2xl border border-outline-variant bg-surface focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none transition-all shadow-sm">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-text">Alamat Pengiriman</label>
            <textarea name="alamatPemesan" rows="3" required
                      class="w-full px-4 py-3 rounded-2xl border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all shadow-sm resize-none"><?= htmlspecialchars($order->getAlamatPemesan()) ?></textarea>
        </div>

        <div class="pt-6 mt-2 border-t border-outline-variant">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-text">Daftar Barang Pesanan</h3>
                <button type="button" id="addItemBtn"
                        class="px-4 py-2 bg-secondary/10 text-secondary font-bold rounded-xl hover:bg-secondary/20 transition-colors text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Barang
                </button>
            </div>

            <div id="orderItemsContainer" class="space-y-4"></div>

            <div class="mt-6 p-4 rounded-xl border border-outline-variant bg-slate-50 dark:bg-slate-800/50 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-muted uppercase tracking-widest">Sub Total Tagihan</span>
                    <span id="subTotalDisplay" class="text-2xl font-black text-primary">Rp. 0</span>
                </div>
                <div class="flex justify-between items-center border-t border-outline-variant/40 pt-2">
                    <span class="text-sm font-medium text-on-surface-variant">Margin Pendapatan</span>
                    <span id="marginDisplay" class="text-xl font-black text-green-600">Rp. 0</span>
                </div>
                <input type="hidden" name="subTotal" id="subTotalInput" value="0">
                <input type="hidden" name="list_item_order" id="listItemOrderInput" value="[]">
            </div>
        </div>

        <div class="pt-6 flex flex-col-reverse sm:flex-row justify-end gap-4">
            <a href="?page=orders&action=show&id=<?= urlencode($order->getOrderNumber()) ?>"
               class="px-6 py-3.5 rounded-2xl border border-outline-variant text-text hover:bg-background transition-colors font-bold text-center">Batal</a>
            <button type="submit"
                    class="px-8 py-3.5 rounded-2xl bg-primary text-white shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-0.5 transition-all font-bold flex items-center justify-center gap-2">
                Simpan Perubahan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </form>
</div>

<script>
const CATALOG       = <?= json_encode(array_values($catalogProducts)) ?>;
const existingItems = <?= json_encode(array_values($existingItems)) ?>;

(function () {
    const container          = document.getElementById('orderItemsContainer');
    const subTotalDisplay    = document.getElementById('subTotalDisplay');
    const subTotalInput      = document.getElementById('subTotalInput');
    const listItemOrderInput = document.getElementById('listItemOrderInput');
    const marginDisplay      = document.getElementById('marginDisplay');

    function fmt(n) {
        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
    }

    function productOptions(selectedName) {
        let opts = `<option value="">— Isi Manual —</option>`;
        CATALOG.forEach(p => {
            const sel = p.name === selectedName ? 'selected' : '';
            opts += `<option value="${p.id}" data-name="${p.name.replace(/"/g,'&quot;')}" data-price="${p.price}" data-modal="${p.modal}" ${sel}>${p.name}</option>`;
        });
        return opts;
    }

    function createItemRow(item = {}) {
        const isManual = !item.name || !CATALOG.find(p => p.name === item.name);
        const div = document.createElement('div');
        div.className = 'order-item flex flex-col gap-3 p-4 rounded-xl border border-outline-variant bg-surface';
        div.innerHTML = `
            <div class="flex flex-col md:flex-row gap-3 items-start md:items-end">
                <div class="flex-1 space-y-1 min-w-0">
                    <label class="text-xs font-semibold text-muted">Produk / Barang</label>
                    <select class="item-product-select w-full px-3 py-2 rounded-lg border border-outline-variant bg-background text-sm focus:ring-1 focus:ring-primary outline-none">
                        ${productOptions(item.name ?? '')}
                    </select>
                </div>
                <div class="item-manual-wrap flex-1 space-y-1 min-w-0${isManual ? '' : ' hidden'}">
                    <label class="text-xs font-semibold text-muted">Nama Manual</label>
                    <input type="text" class="item-name w-full px-3 py-2 rounded-lg border border-outline-variant bg-background text-sm focus:ring-1 focus:ring-primary outline-none"
                           placeholder="Ketik nama barang..." value="${(item.name ?? '').replace(/"/g,'&quot;')}">
                </div>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-3 items-start">
                <div class="space-y-1 sm:flex-1 sm:min-w-[10rem]">
                    <label class="text-xs font-semibold text-muted">Modal (Harga Beli)</label>
                    <input type="number" class="item-modal w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-background text-sm focus:ring-1 focus:ring-primary outline-none" min="0" value="${item.modal ?? 0}">
                    <p class="modal-hint text-[10px] text-on-surface-variant font-mono font-bold mt-1"></p>
                </div>
                <div class="space-y-1 sm:flex-1 sm:min-w-[10rem]">
                    <label class="text-xs font-semibold text-muted">Harga Jual</label>
                    <input type="number" class="item-price w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-background text-sm focus:ring-1 focus:ring-primary outline-none" required min="0" value="${item.price ?? 0}">
                    <p class="price-hint text-[10px] text-primary font-mono font-bold mt-1"></p>
                </div>
                <div class="space-y-1 w-full sm:w-20 sm:shrink-0">
                    <label class="text-xs font-semibold text-muted">Jumlah</label>
                    <input type="number" class="item-qty w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-background text-sm focus:ring-1 focus:ring-primary outline-none" required min="1" value="${item.qty ?? 1}">
                </div>
                <div class="pt-5 shrink-0">
                    <button type="button" class="remove-item px-3 py-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors opacity-60 hover:opacity-100" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        `;
        const sel   = div.querySelector('.item-product-select');
        const wrap  = div.querySelector('.item-manual-wrap');
        const nameI = div.querySelector('.item-name');
        sel.addEventListener('change', () => {
            const opt = sel.selectedOptions[0];
            if (!sel.value) {
                wrap.classList.remove('hidden');
                nameI.required = true;
            } else {
                wrap.classList.add('hidden');
                nameI.required = false;
                div.querySelector('.item-modal').value = opt.dataset.modal;
                div.querySelector('.item-price').value = opt.dataset.price;
            }
            calculateTotal();
        });
        return div;
    }

    function getItemName(row) {
        const sel = row.querySelector('.item-product-select');
        if (sel.value) return sel.selectedOptions[0].dataset.name;
        return row.querySelector('.item-name').value;
    }

    function calculateTotal() {
        let total = 0, totalModal = 0;
        const items = [];
        document.querySelectorAll('.order-item').forEach(row => {
            const name  = getItemName(row);
            const modal = parseFloat(row.querySelector('.item-modal').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const qty   = parseInt(row.querySelector('.item-qty').value) || 0;
            if (name) items.push({ name, price, qty, modal });
            total      += price * qty;
            totalModal += modal * qty;
            const mh = row.querySelector('.modal-hint');
            const ph = row.querySelector('.price-hint');
            if (mh) mh.textContent = modal > 0 ? fmt(modal) : '';
            if (ph) ph.textContent = price > 0 ? fmt(price) : '';
        });
        const margin = total - totalModal;
        subTotalDisplay.textContent = fmt(total);
        subTotalInput.value = total;
        listItemOrderInput.value = JSON.stringify(items);
        marginDisplay.textContent = fmt(margin);
        marginDisplay.className   = margin >= 0 ? 'text-xl font-black text-green-600' : 'text-xl font-black text-red-500';
    }

    document.getElementById('addItemBtn').addEventListener('click', () => { container.appendChild(createItemRow()); calculateTotal(); });

    container.addEventListener('click', e => {
        if (e.target.closest('.remove-item')) {
            if (container.querySelectorAll('.order-item').length > 1) {
                e.target.closest('.order-item').remove(); calculateTotal();
            } else { alert('Minimal harus ada 1 barang dalam pesanan.'); }
        }
    });

    container.addEventListener('input', e => {
        if (['item-price','item-modal','item-qty','item-name'].some(c => e.target.classList.contains(c))) calculateTotal();
    });

    document.querySelector('form').addEventListener('submit', e => {
        calculateTotal();
        if (JSON.parse(listItemOrderInput.value).length === 0) {
            e.preventDefault(); alert('Harap isi minimal 1 barang pesanan.');
        }
    });

    // Pre-populate
    (existingItems.length ? existingItems : [{}]).forEach(item => container.appendChild(createItemRow(item)));
    calculateTotal();
}());
</script>
