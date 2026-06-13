<div class="px-4 md:px-margin-desktop py-10 max-w-xl">
    <div class="mb-8 flex items-center gap-4">
        <a href="?page=laporan" class="bg-surface-container-low text-on-surface p-3 rounded-full hover:bg-outline-variant/30 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-3xl font-bold text-primary">Edit Pengeluaran</h1>
            <p class="text-on-surface-variant mt-1">Ubah data pengeluaran yang sudah diinput.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="glass-panel p-8 rounded-2xl border border-outline-variant/30">
        <form action="?page=laporan&action=updatePengeluaran&id=<?= $pengeluaran->getId() ?>" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">

            <div class="space-y-2">
                <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Tanggal *</label>
                <input type="date" name="tanggal" required
                       value="<?= $pengeluaran->getTanggal()->format('Y-m-d') ?>"
                       class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Keterangan *</label>
                <input type="text" name="keterangan" required
                       value="<?= htmlspecialchars($pengeluaran->getKeterangan()) ?>"
                       class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Jumlah (Rp) *</label>
                <input type="number" name="jumlah" id="inp-edit-jumlah" required min="1" step="any"
                       value="<?= htmlspecialchars($pengeluaran->getJumlah()) ?>"
                       class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                <p id="hint-edit-jumlah" class="text-xs text-primary font-mono font-bold"></p>
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex gap-3 justify-end">
                <a href="?page=laporan" class="px-6 py-3 rounded-xl border border-outline-variant/50 text-on-surface font-bold hover:bg-surface-container transition-colors">Batal</a>
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<script>
(function() {
    const el   = document.getElementById('inp-edit-jumlah');
    const hint = document.getElementById('hint-edit-jumlah');
    function update() {
        const v = parseFloat(el.value) || 0;
        hint.textContent = v > 0 ? 'Rp. ' + new Intl.NumberFormat('id-ID').format(v) : '';
    }
    el.addEventListener('input', update);
    update();
}());
</script>
