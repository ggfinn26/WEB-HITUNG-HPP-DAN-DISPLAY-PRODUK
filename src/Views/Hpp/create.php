<div class="px-4 md:px-margin-desktop py-12 max-w-5xl">
    <div class="mb-8 flex items-center gap-4">
        <a href="?page=hpp" class="bg-surface-container-low text-on-surface p-3 rounded-full hover:bg-outline-variant/30 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-4xl font-bold text-primary mb-1">Kalkulasi Harga Jual</h1>
            <p class="text-on-surface-variant">Masukkan biaya item jastip dan sistem hitung HPP dasar serta harga jual otomatis.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Info Jastip -->
    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 mb-8 flex gap-4">
        <span class="material-symbols-outlined text-primary text-3xl flex-shrink-0">info</span>
        <div class="text-sm text-on-surface-variant leading-relaxed">
            <p class="font-bold text-on-surface mb-1">HPP Dasar vs Harga Jual Akhir</p>
            <p>Kalkulasi ini menghitung <strong>HPP Dasar</strong> — biaya per item sebelum biaya trip (tiket, porter, dll).
               Biaya trip dialokasikan terpisah lewat fitur <a href="?page=sesi" class="text-primary font-bold hover:underline">Sesi Trip</a>
               dan akan menghasilkan <em>sugesti harga jual akhir</em> yang memperhitungkan semua biaya.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form id="hpp-form" action="?page=hpp&action=store" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                <input type="hidden" name="product_item_list" id="product_item_list_input">

                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">Nama Produk *</label>
                    <input type="text" name="name" required placeholder="Misal: Kain Sutra Toraja, Kerajinan Perak"
                           class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>

                <!-- Rincian Biaya -->
                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-bold text-on-surface text-lg">Rincian Biaya per Item</h3>
                        <button type="button" id="add-bahan-btn"
                                class="bg-secondary/10 text-secondary font-bold px-4 py-2 rounded-xl hover:bg-secondary/20 transition-colors text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Biaya
                        </button>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-4">Masukkan semua biaya yang langsung melekat pada item: harga beli, packing, ongkos kirim dari toko, dll. Biaya trip (tiket, porter) diinput di Sesi Trip.</p>

                    <div class="hidden sm:flex mb-3 gap-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider px-4">
                        <div class="flex-1">Komponen Biaya</div>
                        <div class="w-48 flex gap-2">
                            <div class="flex-1 text-right">Biaya (Rp.)</div>
                            <div class="w-8"></div>
                        </div>
                    </div>

                    <div id="bahan-container" class="space-y-2"></div>
                </div>

                <!-- Batch & Margin -->
                <div class="glass-panel p-4 sm:p-6 rounded-2xl border border-outline-variant/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">
                                Jumlah Item dalam Batch *
                            </label>
                            <input type="number" name="jumlah_produksi" id="jumlah_produksi" required min="1" value="1"
                                   class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            <p class="text-xs text-on-surface-variant mt-1">Biasanya 1. Isi lebih dari 1 hanya jika ada biaya bersama yang dibagi rata antar item.</p>
                        </div>
                        <div>
                            <label class="block font-bold text-on-surface text-sm uppercase tracking-wider mb-2">
                                Margin Keuntungan (Rp.) *
                            </label>
                            <input type="number" name="margin_keuntungan" id="margin_keuntungan" required min="0" value="0"
                                   class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            <p class="text-xs text-on-surface-variant mt-1">Keuntungan per item sebelum biaya trip. <span id="margin-hint" class="font-mono font-bold text-primary"></span></p>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submit-btn"
                        class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-lg active:scale-95 flex items-center justify-center gap-2 text-lg">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Kalkulasi
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
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-xs text-yellow-800">
                    <p class="font-bold mb-1">💡 Harga ini belum termasuk biaya trip</p>
                    <p>Setelah HPP disimpan, buka <a href="?page=sesi&action=create" class="font-bold underline">Sesi Trip</a> untuk hitung sugesti harga jual akhir yang sudah memperhitungkan tiket dan porter.</p>
                </div>
                <p class="text-xs text-on-surface-variant text-center italic">Kalkulasi diperbarui otomatis saat kamu mengisi form.</p>
            </div>
        </div>
    </div>
</div>

<script>
function formatRp(num) {
    return 'Rp. ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
}

function createBahanRow(index, defaultNama = '', defaultHarga = 0) {
    const div = document.createElement('div');
    div.className = 'bahan-row flex flex-col sm:flex-row gap-2 items-start p-3 rounded-xl border border-outline-variant/40 bg-surface-container hover:bg-surface transition-colors';
    div.dataset.index = index;

    div.innerHTML = `
        <div class="w-full sm:flex-1">
            <input type="text" placeholder="Nama komponen biaya..." data-field="nama"
                   value="${defaultNama.replace(/"/g,'&quot;')}"
                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
        </div>
        <div class="w-full sm:w-48 flex gap-2">
            <div class="flex-1">
                <input type="number" placeholder="0" min="0" value="${defaultHarga}" data-field="harga"
                       class="w-full px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none text-right" required>
                <p class="harga-hint text-[10px] text-primary font-mono font-bold mt-1 text-right"></p>
            </div>
            <div class="pt-1">
                <button type="button" class="remove-bahan text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
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
    const rows = document.querySelectorAll('.bahan-row');
    return Array.from(rows).map(row => ({
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

    const total    = items.reduce((sum, item) => sum + item.harga, 0);
    const hpp      = jumlahProduksi > 0 ? total / jumlahProduksi : 0;
    const hargaJual = hpp + margin;

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
            row.remove();
            recalculate();
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
        showAlert('Minimal satu komponen biaya harus diisi.', 'warning');
        return;
    }
    document.getElementById('product_item_list_input').value = JSON.stringify(items);
});

// Default rows untuk jastip
addRow('Harga Beli (struk)', 0);
addRow('Biaya Packing', 0);
</script>
