<div class="px-4 md:px-margin-desktop py-10 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="?page=sesi" class="p-2 rounded-xl bg-surface-container border border-outline-variant hover:border-primary transition-colors text-muted hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-widest mb-1">
                    <span class="material-symbols-outlined text-[14px]">analytics</span>
                    Cost-Volume-Profit Analysis
                </div>
                <h1 class="text-3xl font-black text-on-surface tracking-tight">Simulasi Sesi Trip Baru</h1>
            </div>
        </div>
        <p class="text-on-surface-variant text-sm ml-11">
            Masukkan asumsi biaya tetap dan rata-rata harga. Simulator menghitung BEP dan proyeksi laba secara langsung.
        </p>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form id="sesi-form" action="?page=sesi&action=store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
        <input type="hidden" name="komponen" id="komponen-json">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- ── Kolom Kiri: Input ─────────────────────────────────────── -->
            <div class="lg:col-span-2 space-y-5">

                <!-- Identitas Sesi -->
                <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-4">
                    <h3 class="font-bold text-on-surface text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">calendar_month</span>
                        Identitas Sesi
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Nama Sesi</label>
                        <input type="text" name="nama_sesi" required
                               placeholder="cth: Trip Toraja Juni 2025"
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Tanggal Berangkat</label>
                        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Catatan <span class="font-normal normal-case">(opsional)</span></label>
                        <textarea name="catatan" rows="2" placeholder="Tujuan, info trip, dll..."
                                  class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm resize-none"></textarea>
                    </div>
                </div>

                <!-- Biaya Tetap Sesi -->
                <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-on-surface text-sm uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500 text-[18px]">receipt_long</span>
                            Biaya Tetap Sesi
                        </h3>
                        <button type="button" id="btn-add-komponen"
                                class="text-xs font-bold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">add</span> Tambah
                        </button>
                    </div>
                    <p class="text-xs text-on-surface-variant -mt-2">Tiket transport, ongkos porter, dll — biaya yang dibagi ke semua order.</p>

                    <div id="komponen-list" class="space-y-2"></div>

                    <div class="border-t border-outline-variant/30 pt-3 flex justify-between items-center">
                        <span class="text-sm font-bold text-on-surface-variant">Total Biaya Tetap</span>
                        <span id="total-biaya-display" class="text-lg font-black text-red-500">Rp. 0</span>
                    </div>
                </div>

                <!-- Rata-rata Harga -->
                <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-4">
                    <h3 class="font-bold text-on-surface text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600 text-[18px]">price_change</span>
                        Rata-rata per Item
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Rata-rata Harga Jual di Web (Rp)
                        </label>
                        <input type="number" id="input-harga-jual" name="rata_harga_jual"
                               min="1" step="1000" value="150000" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">
                            Rata-rata HPP Dasar per Item (Rp)
                        </label>
                        <input type="number" id="input-hpp-dasar" name="rata_hpp_dasar"
                               min="1" step="1000" value="110000" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                        <p class="text-xs text-on-surface-variant mt-1">Harga beli struk + ongkos porter + packing. Belum termasuk tiket.</p>
                    </div>

                    <!-- Margin kotor live -->
                    <div class="flex justify-between items-center bg-surface-container-low rounded-xl px-4 py-3">
                        <span class="text-sm text-on-surface-variant font-medium">Margin Kotor / item</span>
                        <span id="display-margin-kotor" class="text-base font-black text-green-600">Rp. 40.000</span>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Sesi
                </button>
            </div>

            <!-- ── Kolom Kanan: Hasil CVP ─────────────────────────────────── -->
            <div class="lg:col-span-3 space-y-5">

                <!-- BEP Card -->
                <div id="bep-card" class="rounded-2xl p-6 border-2 border-primary/30 bg-primary/5 space-y-1">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Break-Even Point</p>
                            <p class="text-5xl font-black text-primary" id="bep-value">20</p>
                            <p class="text-sm text-on-surface-variant mt-1">item minimum agar biaya tetap tertutupi</p>
                        </div>
                        <div class="text-right space-y-2">
                            <div class="bg-surface rounded-xl px-4 py-2 border border-outline-variant/30">
                                <p class="text-xs text-on-surface-variant">Margin Kotor</p>
                                <p class="font-black text-on-surface" id="bep-margin-kotor">Rp. 40.000</p>
                            </div>
                            <div class="bg-surface rounded-xl px-4 py-2 border border-outline-variant/30">
                                <p class="text-xs text-on-surface-variant">Total Biaya Tetap</p>
                                <p class="font-black text-red-500" id="bep-biaya-tetap">Rp. 0</p>
                            </div>
                        </div>
                    </div>
                    <div id="bep-desc" class="text-xs text-on-surface-variant mt-3 pt-3 border-t border-primary/20">
                        Pada <strong id="bep-num">20</strong> item, seluruh margin kotor habis untuk menutup biaya tetap. Di bawah angka ini Anda rugi; di atasnya Anda untung.
                    </div>
                </div>

                <!-- Scenario Table -->
                <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <div class="px-6 py-4 border-b border-outline-variant/30 flex items-center justify-between">
                        <h3 class="font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">table_chart</span>
                            Template Analisis Subsidi Margin
                        </h3>
                        <span class="text-xs text-on-surface-variant">Edit qty skenario langsung di tabel</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="cvp-table">
                            <thead>
                                <tr class="bg-surface-container-low">
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">Metrik Evaluasi</th>
                                    <th class="px-4 py-3 text-center">
                                        <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Skenario A</div>
                                        <input type="number" id="qty-a" value="10" min="1"
                                               class="w-20 text-center font-black text-sm border-2 border-outline-variant/50 rounded-lg px-2 py-1 focus:border-primary outline-none bg-surface">
                                        <div class="text-[10px] text-on-surface-variant mt-0.5">item</div>
                                    </th>
                                    <th class="px-4 py-3 text-center bg-primary/5">
                                        <div class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Skenario B</div>
                                        <input type="number" id="qty-b" value="40" min="1"
                                               class="w-20 text-center font-black text-sm border-2 border-primary/40 rounded-lg px-2 py-1 focus:border-primary outline-none bg-surface">
                                        <div class="text-[10px] text-primary mt-0.5">item</div>
                                    </th>
                                    <th class="px-4 py-3 text-center">
                                        <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Skenario C</div>
                                        <input type="number" id="qty-c" value="80" min="1"
                                               class="w-20 text-center font-black text-sm border-2 border-outline-variant/50 rounded-lg px-2 py-1 focus:border-primary outline-none bg-surface">
                                        <div class="text-[10px] text-on-surface-variant mt-0.5">item</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <tr class="hover:bg-surface-container/40 transition-colors">
                                    <td class="px-4 py-3 text-on-surface-variant text-xs">Beban Biaya Tetap / item<br><span class="text-[10px] opacity-60">Total Tetap ÷ Jumlah Order</span></td>
                                    <td id="row-beban-a" class="px-4 py-3 text-center font-bold text-red-500">—</td>
                                    <td id="row-beban-b" class="px-4 py-3 text-center font-bold text-red-500 bg-primary/5">—</td>
                                    <td id="row-beban-c" class="px-4 py-3 text-center font-bold text-red-500">—</td>
                                </tr>
                                <tr class="hover:bg-surface-container/40 transition-colors">
                                    <td class="px-4 py-3 text-on-surface-variant text-xs">Subsidi dari Margin<br><span class="text-[10px] opacity-60">% margin yang terserap biaya tetap</span></td>
                                    <td id="row-subsidi-a" class="px-4 py-3 text-center font-bold text-orange-500">—</td>
                                    <td id="row-subsidi-b" class="px-4 py-3 text-center font-bold text-orange-500 bg-primary/5">—</td>
                                    <td id="row-subsidi-c" class="px-4 py-3 text-center font-bold text-orange-500">—</td>
                                </tr>
                                <tr class="hover:bg-surface-container/40 transition-colors">
                                    <td class="px-4 py-3 text-on-surface font-medium text-xs">Laba Bersih Riil / item<br><span class="text-[10px] text-on-surface-variant">Margin Kotor − Beban Tetap</span></td>
                                    <td id="row-laba-item-a" class="px-4 py-3 text-center font-bold">—</td>
                                    <td id="row-laba-item-b" class="px-4 py-3 text-center font-bold bg-primary/5">—</td>
                                    <td id="row-laba-item-c" class="px-4 py-3 text-center font-bold">—</td>
                                </tr>
                                <tr class="bg-surface-container-low hover:bg-surface-container transition-colors">
                                    <td class="px-4 py-3 text-on-surface font-bold text-sm">TOTAL LABA BERSIH SESI</td>
                                    <td id="row-total-a" class="px-4 py-3 text-center font-black text-base">—</td>
                                    <td id="row-total-b" class="px-4 py-3 text-center font-black text-base bg-primary/5">—</td>
                                    <td id="row-total-c" class="px-4 py-3 text-center font-black text-base">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Visual BEP bar -->
                <div class="glass-panel p-5 rounded-2xl border border-outline-variant/30">
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-3">Visualisasi Titik Impas</p>
                    <div class="relative h-8 bg-gradient-to-r from-red-100 via-yellow-100 to-green-100 rounded-full overflow-hidden border border-outline-variant/20">
                        <div id="bep-marker" class="absolute top-0 bottom-0 w-0.5 bg-primary shadow-sm transition-all duration-300" style="left:50%"></div>
                        <div class="absolute inset-0 flex items-center justify-between px-3 text-[10px] font-bold">
                            <span class="text-red-500">Rugi</span>
                            <span id="bep-bar-label" class="text-primary">BEP: 20 item</span>
                            <span class="text-green-600">Untung</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-[10px] text-on-surface-variant mt-1 px-1">
                        <span>0 item</span>
                        <span id="bep-bar-max">100 item</span>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
const fmt = n => 'Rp. ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
const fmtSign = n => (n >= 0 ? '+' : '') + fmt(n);

// ── Komponen Biaya Tetap ─────────────────────────────────────────────────────
let komponenCount = 0;
const defaultKomponen = [
    { nama: 'Tiket Kapal', jumlah: 400000 },
    { nama: 'Tiket Kereta', jumlah: 400000 },
];

function addKomponen(nama = '', jumlah = 0) {
    const id = komponenCount++;
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 komponen-row';
    div.dataset.id = id;
    div.innerHTML = `
        <input type="text" placeholder="Nama komponen..." value="${nama}"
               class="flex-1 px-3 py-2 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none komponen-nama"
               required>
        <input type="number" placeholder="Jumlah" min="0" step="1000" value="${jumlah}"
               class="w-32 px-3 py-2 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none text-right komponen-jumlah"
               required>
        <button type="button" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors remove-komponen">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>`;
    div.querySelector('.remove-komponen').addEventListener('click', () => {
        if (document.querySelectorAll('.komponen-row').length > 1) {
            div.remove(); recalcAll();
        }
    });
    div.querySelectorAll('input').forEach(el => el.addEventListener('input', recalcAll));
    document.getElementById('komponen-list').appendChild(div);
    recalcAll();
}

document.getElementById('btn-add-komponen').addEventListener('click', () => addKomponen());
defaultKomponen.forEach(k => addKomponen(k.nama, k.jumlah));

// ── Master Recalculate ───────────────────────────────────────────────────────
function recalcAll() {
    const totalBiaya = getTotalBiaya();
    const hargaJual  = parseFloat(document.getElementById('input-harga-jual').value) || 0;
    const hppDasar   = parseFloat(document.getElementById('input-hpp-dasar').value)  || 0;
    const marginKotor = hargaJual - hppDasar;

    // Update komponen JSON
    const rows = document.querySelectorAll('.komponen-row');
    const kompData = Array.from(rows).map(r => ({
        nama:   r.querySelector('.komponen-nama').value.trim(),
        jumlah: parseFloat(r.querySelector('.komponen-jumlah').value) || 0,
    })).filter(k => k.nama && k.jumlah > 0);
    document.getElementById('komponen-json').value = JSON.stringify(kompData);

    // Top displays
    document.getElementById('total-biaya-display').textContent = fmt(totalBiaya);
    document.getElementById('display-margin-kotor').textContent = fmt(marginKotor);
    document.getElementById('bep-biaya-tetap').textContent = fmt(totalBiaya);
    document.getElementById('bep-margin-kotor').textContent = fmt(marginKotor);

    // BEP
    const bep = marginKotor > 0 ? (totalBiaya / marginKotor) : null;
    const bepDisplay = bep !== null ? Math.ceil(bep) : '∞';
    document.getElementById('bep-value').textContent = bepDisplay;
    document.getElementById('bep-num').textContent   = bepDisplay;
    document.getElementById('bep-bar-label').textContent = `BEP: ${bepDisplay} item`;

    // BEP bar marker
    const qtyMax = Math.max(
        parseFloat(document.getElementById('qty-a').value) || 10,
        parseFloat(document.getElementById('qty-b').value) || 40,
        parseFloat(document.getElementById('qty-c').value) || 80,
        bep ? Math.ceil(bep) * 1.5 : 100
    );
    document.getElementById('bep-bar-max').textContent = Math.ceil(qtyMax) + ' item';
    const pct = bep ? Math.min(100, Math.round((bep / qtyMax) * 100)) : 50;
    document.getElementById('bep-marker').style.left = pct + '%';

    // Scenario table
    ['a', 'b', 'c'].forEach(s => {
        const qty = parseInt(document.getElementById(`qty-${s}`).value) || 0;
        if (qty <= 0 || totalBiaya <= 0) { clearScenario(s); return; }

        const bebanPerItem  = totalBiaya / qty;
        const labaPerItem   = marginKotor - bebanPerItem;
        const subsidiFrac   = marginKotor > 0 ? Math.min(1, bebanPerItem / marginKotor) : 0;
        const totalLaba     = labaPerItem * qty;
        const isRugi        = labaPerItem < 0;

        set(`row-beban-${s}`,       fmt(bebanPerItem),   'text-red-500');
        set(`row-subsidi-${s}`,     (subsidiFrac * 100).toFixed(1) + '%', isRugi ? 'text-red-500 font-black' : 'text-orange-500');
        set(`row-laba-item-${s}`,   fmtSign(labaPerItem), isRugi ? 'text-red-600 font-black' : 'text-green-600 font-black');
        set(`row-total-${s}`,       fmtSign(totalLaba),   isRugi ? 'text-red-600 font-black text-base' : 'text-green-600 font-black text-base');
    });
}

function getTotalBiaya() {
    return Array.from(document.querySelectorAll('.komponen-jumlah'))
                .reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
}

function clearScenario(s) {
    ['beban','subsidi','laba-item','total'].forEach(r => set(`row-${r}-${s}`, '—', 'text-on-surface-variant'));
}

function set(id, text, cls) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = text;
    el.className = el.className.replace(/text-\S+/g, '');
    el.className += ' ' + cls + ' px-4 py-3 text-center ' + (id.startsWith('row-total') ? 'font-black text-base' : 'font-bold');
    if (id.includes('-b')) el.className += ' bg-primary/5';
}

// Wire scenario qty inputs
['qty-a', 'qty-b', 'qty-c'].forEach(id =>
    document.getElementById(id).addEventListener('input', recalcAll)
);
document.getElementById('input-harga-jual').addEventListener('input', recalcAll);
document.getElementById('input-hpp-dasar').addEventListener('input', recalcAll);

recalcAll();
</script>
