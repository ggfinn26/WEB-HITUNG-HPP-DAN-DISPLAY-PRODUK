<div class="px-4 md:px-margin-desktop py-10 max-w-7xl mx-auto">

    <div class="mb-8 flex items-center gap-3">
        <a href="?page=sesi" class="p-2 rounded-xl bg-surface-container border border-outline-variant hover:border-primary transition-colors text-muted hover:text-primary">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight">Buat Sesi Trip Baru</h1>
            <p class="text-on-surface-variant text-sm mt-0.5">Pilih produk, estimasi order, dan sistem hitung sugesti harga serta BEP secara otomatis.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form id="sesi-form" action="?page=sesi&action=store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
        <input type="hidden" name="komponen" id="komponen-json">
        <input type="hidden" name="produk_json" id="produk-json">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- ── Kolom Kiri: Input ── -->
            <div class="lg:col-span-2 space-y-5">

                <!-- Identitas -->
                <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-4">
                    <h3 class="font-bold text-on-surface text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">calendar_month</span>
                        Identitas Sesi
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Nama Sesi *</label>
                        <input type="text" name="nama_sesi" required placeholder="cth: Trip Toraja Juni 2025"
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Tanggal Berangkat *</label>
                        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Catatan <span class="font-normal normal-case">(opsional)</span></label>
                        <textarea name="catatan" rows="2" placeholder="Tujuan, info trip, dll..."
                                  class="w-full px-4 py-3 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm resize-none"></textarea>
                    </div>
                </div>

                <!-- Biaya Tetap -->
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
                    <p class="text-xs text-on-surface-variant -mt-2">Tiket, porter, dll — akan otomatis masuk pengeluaran saat sesi ditutup.</p>
                    <div id="komponen-list" class="space-y-2"></div>
                    <div class="border-t border-outline-variant/30 pt-3 flex justify-between items-center">
                        <span class="text-sm font-bold text-on-surface-variant">Total Biaya Tetap</span>
                        <span id="total-biaya-display" class="text-lg font-black text-red-500">Rp. 0</span>
                    </div>
                </div>

                <!-- Distribusi -->
                <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-4">
                    <h3 class="font-bold text-on-surface text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-container text-[18px]">tune</span>
                        Metode Distribusi Biaya
                    </h3>
                    <p class="text-xs text-on-surface-variant -mt-2">Berapa % biaya tetap didistribusi proporsional (produk mahal menanggung lebih). Sisanya dibagi rata per item.</p>
                    <div class="flex items-center gap-4">
                        <input type="range" id="persen-slider" name="persen_proporsional"
                               min="0" max="100" value="60" step="10"
                               class="flex-1 accent-primary">
                        <div class="text-right min-w-[80px]">
                            <span id="persen-display" class="text-2xl font-black text-primary">60%</span>
                            <p class="text-[10px] text-on-surface-variant">proporsional</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-primary/5 rounded-lg px-3 py-2 text-center">
                            <p class="text-on-surface-variant">Proporsional (nilai)</p>
                            <p class="font-black text-primary" id="label-prop">60%</p>
                        </div>
                        <div class="bg-surface-container rounded-lg px-3 py-2 text-center">
                            <p class="text-on-surface-variant">Rata per item</p>
                            <p class="font-black text-on-surface" id="label-rata">40%</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Sesi
                </button>
            </div>

            <!-- ── Kolom Kanan: Pilih Produk + Hasil ── -->
            <div class="lg:col-span-3 space-y-5">

                <!-- Pilih Produk -->
                <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant/30 flex items-center justify-between">
                        <h3 class="font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">inventory_2</span>
                            Pilih Produk untuk Sesi Ini
                        </h3>
                        <span class="text-xs text-on-surface-variant" id="label-produk-terpilih">0 produk dipilih</span>
                    </div>

                    <?php if (empty($hppForSesi)): ?>
                        <div class="p-8 text-center text-on-surface-variant text-sm">
                            Belum ada HPP terdaftar. <a href="?page=hpp&action=create" class="text-primary font-bold hover:underline">Buat HPP dulu</a>.
                        </div>
                    <?php else: ?>
                    <div class="divide-y divide-outline-variant/20" id="produk-list">
                        <?php foreach ($hppForSesi as $h): ?>
                        <div class="produk-row flex items-center gap-3 px-5 py-3 hover:bg-surface-container/40 transition-colors"
                             data-hpp-id="<?= $h['hpp_id'] ?>"
                             data-nama="<?= htmlspecialchars($h['nama']) ?>"
                             data-harga-jual="<?= (float)$h['catalog_price'] ?>"
                             data-hpp-per-pcs="<?= (float)$h['hpp_per_pcs'] ?>"
                             data-margin="<?= (float)$h['margin'] ?>">
                            <input type="checkbox" class="produk-checkbox w-4 h-4 accent-primary rounded flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-on-surface text-sm truncate"><?= htmlspecialchars($h['nama']) ?></p>
                                <p class="text-xs text-on-surface-variant">
                                    HPP: Rp. <?= number_format((float)$h['hpp_per_pcs'], 0, ',', '.') ?>
                                    · Harga: Rp. <?= number_format((float)$h['catalog_price'], 0, ',', '.') ?>
                                    · Margin: Rp. <?= number_format((float)$h['margin'], 0, ',', '.') ?>
                                </p>
                            </div>
                            <div class="qty-wrap hidden flex-shrink-0 gap-2">
                                <div>
                                    <label class="block text-[10px] text-on-surface-variant font-bold uppercase mb-0.5 text-right">Est. Qty</label>
                                    <input type="number" class="produk-qty w-20 px-2 py-1.5 rounded-lg border-2 border-outline-variant/50 bg-surface text-sm text-center focus:border-primary outline-none" min="1" value="1">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-on-surface-variant font-bold uppercase mb-0.5 text-right" title="Bobot Proporsional (Kosongkan = otomatis dari nilai jual)">Bobot Prop.</label>
                                    <input type="number" name="custom_prop[<?= $h['hpp_id'] ?>]" class="produk-prop w-20 px-2 py-1.5 rounded-lg border-2 border-outline-variant/50 bg-surface text-sm text-center focus:border-primary outline-none" placeholder="auto" step="0.01">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-on-surface-variant font-bold uppercase mb-0.5 text-right" title="Bobot Flat (Kosongkan = dibagi rata)">Bobot Flat</label>
                                    <input type="number" name="custom_flat[<?= $h['hpp_id'] ?>]" class="produk-flat w-24 px-2 py-1.5 rounded-lg border-2 border-outline-variant/50 bg-surface text-sm text-center focus:border-primary outline-none" placeholder="auto">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tabel Kalkulasi Live -->
                <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant/30 flex items-center justify-between">
                        <h3 class="font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">calculate</span>
                            Kalkulasi Sugesti Harga
                        </h3>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-on-surface-variant">BEP Total:</span>
                            <span id="bep-display" class="font-black text-primary">— item</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="kalkulasi-table">
                            <thead class="bg-surface-container-low text-xs uppercase tracking-wider text-on-surface-variant">
                                <tr>
                                    <th class="px-4 py-3 text-left">Produk</th>
                                    <th class="px-4 py-3 text-right">Est. Qty</th>
                                    <th class="px-4 py-3 text-right">Beban Trip/item</th>
                                    <th class="px-4 py-3 text-right">Sugesti Harga</th>
                                    <th class="px-4 py-3 text-right">Harga Katalog</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="kalkulasi-body" class="divide-y divide-outline-variant/20">
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-on-surface-variant text-xs italic">Pilih produk di atas untuk melihat kalkulasi.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
const fmt    = n => 'Rp. ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
const slider = document.getElementById('persen-slider');
let komponenCount = 0;

// ── Komponen Biaya Tetap ─────────────────────────────────────────────────────
function addKomponen(nama = '', jumlah = 0) {
    const id  = komponenCount++;
    const div = document.createElement('div');
    div.className = 'flex items-start gap-2 komponen-row';
    div.innerHTML = `
        <input type="text" placeholder="Nama komponen..." value="${nama}"
               class="flex-1 min-w-0 px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none komponen-nama" required>
        <div class="flex-shrink-0 w-44">
            <input type="number" placeholder="Jumlah" min="0" step="1000" value="${jumlah}"
                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant/50 bg-surface text-sm focus:ring-2 focus:ring-primary/20 outline-none text-right komponen-jumlah" required>
            <p class="komponen-hint text-[10px] text-primary font-mono font-bold mt-1 text-right"></p>
        </div>
        <button type="button" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors remove-komponen flex-shrink-0">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>`;
    const jumlahEl = div.querySelector('.komponen-jumlah');
    const hintEl   = div.querySelector('.komponen-hint');
    jumlahEl.addEventListener('input', () => {
        const v = parseFloat(jumlahEl.value) || 0;
        hintEl.textContent = v > 0 ? fmt(v) : '';
        recalc();
    });
    div.querySelector('.remove-komponen').addEventListener('click', () => {
        if (document.querySelectorAll('.komponen-row').length > 1) { div.remove(); recalc(); }
    });
    if (jumlah > 0) hintEl.textContent = fmt(jumlah);
    document.getElementById('komponen-list').appendChild(div);
    recalc();
}

document.getElementById('btn-add-komponen').addEventListener('click', () => addKomponen());
addKomponen('Tiket Kapal', 400000);
addKomponen('Tiket Kereta', 400000);

// ── Slider Persen ─────────────────────────────────────────────────────────────
slider.addEventListener('input', () => {
    const v = slider.value;
    document.getElementById('persen-display').textContent = v + '%';
    document.getElementById('label-prop').textContent     = v + '%';
    document.getElementById('label-rata').textContent     = (100 - v) + '%';
    recalc();
});

// ── Produk Checkbox ───────────────────────────────────────────────────────────
document.querySelectorAll('.produk-checkbox').forEach(cb => {
    cb.addEventListener('change', () => {
        const row = cb.closest('.produk-row');
        const qw  = row.querySelector('.qty-wrap');
        if (cb.checked) { qw.classList.remove('hidden'); qw.classList.add('flex', 'items-center'); }
        else            { qw.classList.add('hidden'); qw.classList.remove('flex', 'items-center'); }
        updateLabelTerpilih();
        recalc();
    });
});
document.querySelectorAll('.produk-qty, .produk-prop, .produk-flat').forEach(el => {
    el.addEventListener('input', recalc);
});

function updateLabelTerpilih() {
    const n = document.querySelectorAll('.produk-checkbox:checked').length;
    document.getElementById('label-produk-terpilih').textContent = n + ' produk dipilih';
}

// ── Master Recalculate ────────────────────────────────────────────────────────
function getTotalBiaya() {
    return Array.from(document.querySelectorAll('.komponen-jumlah'))
                .reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
}

function getSelectedProduk() {
    const result = [];
    document.querySelectorAll('.produk-checkbox:checked').forEach(cb => {
        const row = cb.closest('.produk-row');
        const qty = Math.max(1, parseInt(row.querySelector('.produk-qty').value) || 1);
        const propVal = row.querySelector('.produk-prop').value;
        const flatVal = row.querySelector('.produk-flat').value;
        result.push({
            hpp_id:      parseInt(row.dataset.hppId),
            nama:        row.dataset.nama,
            harga_jual:  parseFloat(row.dataset.hargaJual),
            hpp_per_pcs: parseFloat(row.dataset.hppPerPcs),
            margin:      parseFloat(row.dataset.margin),
            estimasi_qty: qty,
            custom_prop: propVal !== '' ? parseFloat(propVal) : null,
            custom_flat: flatVal !== '' ? parseFloat(flatVal) : null,
        });
    });
    return result;
}

function hitungDistribusi(produkList, totalBiaya, persenProp) {
    const totalQty   = produkList.reduce((s, p) => s + p.estimasi_qty, 0);
    const totalNilai = produkList.reduce((s, p) => s + p.harga_jual * p.estimasi_qty, 0);
    const bagianProp = totalBiaya * (persenProp / 100);
    const bagianRata = totalBiaya * ((100 - persenProp) / 100);
    const bebanRata  = totalQty > 0 ? bagianRata / totalQty : 0;

    return produkList.map(p => {
        let weight = totalNilai > 0 ? (p.harga_jual * p.estimasi_qty) / totalNilai : 1 / produkList.length;
        if (p.custom_prop !== null) weight = p.custom_prop;
        
        const bebanProp  = p.estimasi_qty > 0 ? (bagianProp * weight) / p.estimasi_qty : 0;
        
        let bebanRataItem = bebanRata;
        if (p.custom_flat !== null) bebanRataItem = p.custom_flat;
        
        const beban      = bebanProp + bebanRataItem;
        const trueCost   = p.hpp_per_pcs + beban;
        const sugesti    = trueCost + p.margin;
        const selisih    = p.harga_jual - sugesti;
        return { ...p, beban, trueCost, sugesti, selisih, isBoncos: selisih < 0 };
    });
}

function recalc() {
    const totalBiaya = getTotalBiaya();
    const persen     = parseInt(slider.value);
    const produk     = getSelectedProduk();

    document.getElementById('total-biaya-display').textContent = fmt(totalBiaya);

    // Serialize produk to hidden input
    document.getElementById('komponen-json').value = JSON.stringify(
        Array.from(document.querySelectorAll('.komponen-row')).map(r => ({
            nama:   r.querySelector('.komponen-nama').value.trim(),
            jumlah: parseFloat(r.querySelector('.komponen-jumlah').value) || 0,
        })).filter(k => k.nama && k.jumlah > 0)
    );
    document.getElementById('produk-json').value = JSON.stringify(produk.map(p => ({
        hpp_id: p.hpp_id, nama: p.nama,
        harga_jual: p.harga_jual, hpp_per_pcs: p.hpp_per_pcs,
        margin: p.margin, estimasi_qty: p.estimasi_qty,
    })));

    const tbody = document.getElementById('kalkulasi-body');
    if (produk.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-on-surface-variant text-xs italic">Pilih produk di atas untuk melihat kalkulasi.</td></tr>`;
        document.getElementById('bep-display').textContent = '— item';
        return;
    }

    const hasil = hitungDistribusi(produk, totalBiaya, persen);

    // BEP
    const totalMargin = produk.reduce((s, p) => s + p.margin * p.estimasi_qty, 0);
    const totalQty    = produk.reduce((s, p) => s + p.estimasi_qty, 0);
    const marginRata  = totalQty > 0 ? totalMargin / totalQty : 0;
    const bep         = marginRata > 0 ? Math.ceil(totalBiaya / marginRata) : null;
    document.getElementById('bep-display').textContent = bep !== null ? bep + ' item' : '∞';

    tbody.innerHTML = hasil.map(h => `
        <tr class="hover:bg-surface-container/40 transition-colors">
            <td class="px-4 py-3 font-bold text-on-surface text-sm">${h.nama}</td>
            <td class="px-4 py-3 text-right text-on-surface-variant text-sm">${h.estimasi_qty}</td>
            <td class="px-4 py-3 text-right text-sm font-mono text-red-500">${fmt(h.beban)}</td>
            <td class="px-4 py-3 text-right font-bold text-sm font-mono text-primary">${fmt(h.sugesti)}</td>
            <td class="px-4 py-3 text-right text-sm font-mono ${h.isBoncos ? 'text-red-500 line-through' : 'text-on-surface'}">${fmt(h.harga_jual)}</td>
            <td class="px-4 py-3 text-center">
                ${h.isBoncos
                    ? `<span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full"><span class="material-symbols-outlined text-[12px]">warning</span>Boncos ${fmt(Math.abs(h.selisih))}</span>`
                    : `<span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full"><span class="material-symbols-outlined text-[12px]">check_circle</span>Aman +${fmt(h.selisih)}</span>`
                }
            </td>
        </tr>`).join('');
}

recalc();
</script>
