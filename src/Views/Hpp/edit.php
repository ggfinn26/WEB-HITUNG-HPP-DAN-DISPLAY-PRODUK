<div class="px-4 md:px-margin-desktop py-12 max-w-5xl">
    <div class="mb-8 flex items-center gap-4">
        <a href="?page=hpp" class="bg-surface-container-low text-on-surface p-3 rounded-full hover:bg-outline-variant/30 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-4xl font-bold text-primary mb-1">Edit Kalkulasi HPP</h1>
            <p class="text-on-surface-variant">Perubahan akan memperbarui HPP <strong>dan harga jual produk terkait</strong> secara otomatis.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if ($hpp->getProductId() > 0): ?>
    <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-4 mb-6 flex gap-3 items-start">
        <span class="material-symbols-outlined text-yellow-600 text-2xl flex-shrink-0">sync</span>
        <p class="text-sm text-yellow-800">HPP ini terhubung ke sebuah produk. Saat kamu simpan, <strong>harga jual produk tersebut akan ikut diperbarui</strong> sesuai kalkulasi baru.</p>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <form id="hpp-form" action="?page=hpp&action=update&id=<?= $hpp->getId() ?>" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                <input type="hidden" name="product_item_list" id="product_item_list_input">

                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">Nama Produk *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($hpp->getName()) ?>"
                           class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>

                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-bold text-on-surface text-lg">Rincian Biaya per Item</h3>
                        <button type="button" id="add-bahan-btn"
                                class="bg-secondary/10 text-secondary font-bold px-4 py-2 rounded-xl hover:bg-secondary/20 transition-colors text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Biaya
                        </button>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-4">Harga beli, packing, ongkos kirim dari toko, dll. Biaya trip diinput di Sesi Trip.</p>

                    <div class="hidden sm:flex mb-3 gap-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4">
                        <div class="flex-1">Komponen Biaya</div>
                        <div class="w-48 flex gap-2">
                            <div class="flex-1 text-right">Biaya (Rp.)</div>
                            <div class="w-8"></div>
                        </div>
                    </div>

                    <div id="bahan-container" class="space-y-2"></div>
                </div>

                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">Jumlah Item dalam Batch *</label>
                            <input type="number" name="jumlah_produksi" id="jumlah_produksi" required min="1"
                                   value="<?= $hpp->getJumlahProduksi() ?>"
                                   class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            <p class="text-xs text-on-surface-variant mt-1">Biasanya 1. Isi lebih dari 1 jika ada biaya bersama yang dibagi rata.</p>
                        </div>
                        <div>
                            <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">Margin Keuntungan (Rp.) *</label>
                            <input type="number" name="margin_keuntungan" id="margin_keuntungan" required min="0"
                                   value="<?= $hpp->getMarginKeuntungan() ?>"
                                   class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            <p class="text-xs text-on-surface-variant mt-1">Keuntungan per item sebelum biaya trip. <span id="margin-hint" class="font-mono font-bold text-primary"></span></p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-lg active:scale-95 flex items-center justify-center gap-2 text-lg">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Hasil Kalkulasi Live -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                <div class="bg-primary text-white p-6 rounded-2xl shadow-xl">
                    <h3 class="font-bold text-white/80 text-sm uppercase tracking-wider mb-4">Hasil Kalkulasi</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-white/70 text-xs uppercase tracking-wider">Total Biaya Item</p>
                            <p id="result-total" class="text-2xl font-black">Rp. 0</p>
                        </div>
                        <div class="border-t border-white/20 pt-4">
                            <p class="text-white/70 text-xs uppercase tracking-wider">HPP Dasar / item</p>
                            <p id="result-hpp" class="text-2xl font-black">Rp. 0</p>
                        </div>
                        <div class="border-t border-white/20 pt-4">
                            <p class="text-white/70 text-xs uppercase tracking-wider">+ Margin</p>
                            <p id="result-margin" class="text-lg font-bold opacity-80">Rp. 0</p>
                        </div>
                        <div class="border-t border-white/30 pt-4">
                            <p class="text-white/70 text-xs uppercase tracking-wider mb-1">Harga Jual (tanpa biaya trip)</p>
                            <p id="result-jual" class="text-4xl font-black">Rp. 0</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant text-center italic">Kalkulasi diperbarui otomatis saat kamu mengisi form.</p>
            </div>
        </div>
    </div>
</div>

<script>
const EXISTING_ITEMS = <?= json_encode(array_values($existingItems)) ?>;

function formatRp(num) {
    return 'Rp. ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
}

function createBahanRow(index, defaultNama = '', defaultHarga = 0) {
    const div = document.createElement('div');
    div.className = 'bahan-row grid grid-cols-12 gap-2 items-start p-3 rounded-xl border border-outline-variant/40 bg-surface-container hover:bg-surface transition-colors';
    div.dataset.index = index;
    div.innerHTML = `
        <div class="col-span-12 sm:col-span-7">
            <input type="text" placeholder="Nama komponen biaya..." data-field="nama"
                   value="${defaultNama.replace(/"/g,'&quot;')}"
                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
        </div>
        <div class="col-span-10 sm:col-span-4">
            <input type="number" placeholder="0" min="0" value="${defaultHarga}" data-field="harga"
                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none text-right" required>
            <p class="harga-hint text-[10px] text-primary font-mono font-bold mt-1 text-right"></p>
        </div>
        <div class="col-span-2 sm:col-span-1 flex justify-end pt-1">
            <button type="button" class="remove-bahan text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
        <input type="hidden" data-field="jumlah" value="1">
        <input type="hidden" data-field="kuantitas" value="1">
        <input type="hidden" data-field="satuan" value="">
    `;
    if (defaultHarga > 0) {
        div.querySelector('.harga-hint').textContent = formatRp(defaultHarga);
    }
    return div;
}

function getItemList() {
    return Array.from(document.querySelectorAll('.bahan-row')).map(row => ({
        nama:      row.querySelector('[data-field="nama"]').value,
        jumlah:    parseFloat(row.querySelector('[data-field="jumlah"]').value) || 0,
        satuan:    row.querySelector('[data-field="satuan"]').value,
        kuantitas: parseFloat(row.querySelector('[data-field="kuantitas"]').value) || 0,
        harga:     parseFloat(row.querySelector('[data-field="harga"]').value) || 0,
    }));
}

function recalculate() {
    const items          = getItemList();
    const jumlahProduksi = parseFloat(document.getElementById('jumlah_produksi').value) || 1;
    const margin         = parseFloat(document.getElementById('margin_keuntungan').value) || 0;
    const total          = items.reduce((sum, item) => sum + item.harga, 0);
    const hpp            = jumlahProduksi > 0 ? total / jumlahProduksi : 0;
    const hargaJual      = hpp + margin;

    document.getElementById('result-total').textContent  = formatRp(total);
    document.getElementById('result-hpp').textContent    = formatRp(hpp);
    document.getElementById('result-margin').textContent = formatRp(margin);
    document.getElementById('result-jual').textContent   = formatRp(hargaJual);

    const mh = document.getElementById('margin-hint');
    if (mh) mh.textContent = margin > 0 ? '→ ' + formatRp(margin) : '';

    document.getElementById('product_item_list_input').value = JSON.stringify(items);
}

let rowCount = 0;
const container = document.getElementById('bahan-container');

function addRow(nama = '', harga = 0) {
    const row = createBahanRow(rowCount++, nama, harga);
    container.appendChild(row);
    row.querySelectorAll('input:not([type=hidden])').forEach(el => el.addEventListener('input', recalculate));
    const hargaInput = row.querySelector('[data-field="harga"]');
    const hargaHint  = row.querySelector('.harga-hint');
    hargaInput.addEventListener('input', () => {
        const v = parseFloat(hargaInput.value) || 0;
        hargaHint.textContent = v > 0 ? formatRp(v) : '';
    });
    row.querySelector('.remove-bahan').addEventListener('click', () => {
        if (document.querySelectorAll('.bahan-row').length > 1) {
            row.remove(); recalculate();
        }
    });
    recalculate();
}

document.getElementById('add-bahan-btn').addEventListener('click', () => addRow());
document.getElementById('jumlah_produksi').addEventListener('input', recalculate);
document.getElementById('margin_keuntungan').addEventListener('input', recalculate);

document.getElementById('hpp-form').addEventListener('submit', function(e) {
    const items = getItemList();
    if (items.length === 0 || items.every(i => !i.nama)) {
        e.preventDefault();
        alert('Minimal satu komponen biaya harus diisi.');
        return;
    }
    document.getElementById('product_item_list_input').value = JSON.stringify(items);
});

// Load existing items
if (EXISTING_ITEMS.length > 0) {
    EXISTING_ITEMS.forEach(item => addRow(item.nama || '', item.harga || 0));
} else {
    addRow('Harga Beli (struk)', 0);
    addRow('Biaya Packing', 0);
}
</script>
