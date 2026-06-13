<div class="px-4 md:px-margin-desktop py-10 max-w-6xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="text-xs font-bold text-primary uppercase tracking-widest mb-1 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px]">analytics</span>
                Cost-Volume-Profit Analysis
            </div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight">Analisis Sesi Trip</h1>
            <p class="text-on-surface-variant text-sm mt-1">Rekam biaya tetap per perjalanan dan ukur profitabilitas riil pasca-trip.</p>
        </div>
        <a href="?page=sesi&action=create"
           class="bg-primary text-white px-5 py-3 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-md flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Buat Sesi Baru
        </a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['success_message']) ?><?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($sesiList)): ?>
        <div class="glass-panel p-16 rounded-3xl text-center border border-outline-variant/30">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 block mb-4">analytics</span>
            <h3 class="text-xl font-bold text-on-surface mb-2">Belum Ada Sesi</h3>
            <p class="text-on-surface-variant mb-6 text-sm max-w-md mx-auto">
                Buat sesi trip pertama untuk mulai mensimulasikan CVP dan mengukur profitabilitas riil perjalanan jastip Anda.
            </p>
            <a href="?page=sesi&action=create" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                <span class="material-symbols-outlined">add</span> Buat Sesi Pertama
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($sesiList as $sesi):
            $isDraft = $sesi->getStatus() === 'draft';
        ?>
        <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden hover:border-primary/30 transition-colors">
            <div class="p-5 flex flex-col md:flex-row md:items-center gap-4">

                <!-- Status chip -->
                <div class="flex-shrink-0">
                    <?php if ($isDraft): ?>
                        <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full text-xs font-bold">
                            <span class="material-symbols-outlined text-[12px]">schedule</span> Draft
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-bold">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span> Selesai
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Info utama -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start gap-2 flex-wrap">
                        <h3 class="font-bold text-on-surface text-base leading-tight"><?= htmlspecialchars($sesi->getNamaSesi()) ?></h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        <?= $sesi->getTanggal()->format('d F Y') ?>
                        <?php if ($sesi->getCatatan()): ?>
                            · <?= htmlspecialchars(mb_substr($sesi->getCatatan(), 0, 60)) ?>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Metrics strip -->
                <div class="flex flex-wrap gap-3 flex-shrink-0 text-center">
                    <div class="bg-red-50 border border-red-100 rounded-xl px-3 py-2 min-w-[90px]">
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider">Biaya Tetap</p>
                        <p class="text-sm font-black text-red-500">Rp. <?= number_format($sesi->getTotalBiayaTetap(), 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-primary/5 border border-primary/20 rounded-xl px-3 py-2 min-w-[90px]">
                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">Distribusi</p>
                        <p class="text-sm font-black text-primary"><?= $sesi->getPersenProporsional() ?>% prop.</p>
                    </div>
                    <div class="bg-surface-container border border-outline-variant/40 rounded-xl px-3 py-2 min-w-[90px]">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Detail</p>
                        <p class="text-xs font-bold text-primary">Lihat →</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="?page=sesi&action=detail&id=<?= $sesi->getId() ?>"
                       class="p-2 rounded-xl bg-surface border border-outline-variant hover:border-primary text-muted hover:text-primary transition-colors" title="Detail">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    </a>
                    <?php if ($isDraft): ?>
                    <?php
                        $produkSesiJson = '[]';
                        if (isset($sesiProdukMap[$sesi->getId()])) {
                            $produkSesiJson = json_encode(array_map(fn($p) => [
                                'id'           => $p->getId(),
                                'nama'         => $p->getNamaSnapshot(),
                                'estimasi_qty' => $p->getEstimasiQty(),
                            ], $sesiProdukMap[$sesi->getId()]));
                        }
                    ?>
                    <button type="button"
                            onclick="openTutupModal(<?= $sesi->getId() ?>, '<?= htmlspecialchars(addslashes($sesi->getNamaSesi())) ?>', <?= htmlspecialchars($produkSesiJson) ?>)"
                            class="p-2 rounded-xl bg-green-50 border border-green-200 text-green-600 hover:bg-green-600 hover:text-white transition-colors" title="Tutup Sesi">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </button>
                    <?php endif; ?>
                    <form method="POST" action="?page=sesi&action=hapus&id=<?= $sesi->getId() ?>"
                          onsubmit="return confirm('Hapus sesi \'<?= htmlspecialchars(addslashes($sesi->getNamaSesi())) ?>\'?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                        <button type="submit" class="p-2 rounded-xl bg-red-50 border border-red-100 text-red-400 hover:bg-red-600 hover:text-white transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Tutup Sesi -->
<div id="tutup-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-surface rounded-3xl shadow-2xl p-6 max-w-md w-full">
        <h3 class="font-bold text-lg text-on-surface mb-1">Tutup Sesi</h3>
        <p class="text-sm text-on-surface-variant mb-4" id="tutup-modal-name"></p>
        <form method="POST" id="tutup-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-3">Qty Aktual per Produk</p>
            <div id="tutup-produk-list" class="space-y-2 mb-4 max-h-60 overflow-y-auto pr-1"></div>
            <p class="text-xs text-on-surface-variant mb-4">Biaya tetap sesi akan otomatis masuk ke <strong>Pengeluaran</strong>. Sesi berubah menjadi <strong>Selesai</strong>.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeTutupModal()"
                        class="flex-1 py-2.5 rounded-xl border border-outline-variant text-on-surface font-bold hover:bg-surface-container transition-colors text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 transition-colors text-sm">Tutup Sesi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTutupModal(id, nama, produkList) {
    document.getElementById('tutup-form').action = `?page=sesi&action=tutup&id=${id}`;
    document.getElementById('tutup-modal-name').textContent = `Sesi: ${nama}`;
    const list = document.getElementById('tutup-produk-list');
    const escHtml = s => { const d = document.createElement('div'); d.textContent = s ?? ''; return d.innerHTML; };
    list.innerHTML = produkList.length === 0
        ? '<p class="text-xs text-on-surface-variant italic">Tidak ada produk terdaftar.</p>'
        : produkList.map(p => `
            <div class="flex items-center gap-3 py-2 border-b border-outline-variant/20 last:border-0">
                <span class="flex-1 text-sm font-medium text-on-surface truncate">${escHtml(p.nama)}</span>
                <div class="flex-shrink-0">
                    <label class="block text-[10px] text-on-surface-variant font-bold uppercase mb-0.5 text-right">Aktual</label>
                    <input type="number" name="aktual_qty[${p.id}]" min="0" value="${p.estimasi_qty}"
                           class="w-20 px-2 py-1.5 rounded-lg border-2 border-outline-variant/50 bg-surface-container focus:border-primary outline-none text-sm text-center">
                </div>
            </div>`).join('');
    const modal = document.getElementById('tutup-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeTutupModal() {
    const modal = document.getElementById('tutup-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
