<?php
$namaBulan = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$currentMonthLabel = $namaBulan[$bulan] . ' ' . $tahun;
?>
<div class="px-4 md:px-margin-desktop py-10 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-display-lg text-3xl font-bold text-primary">Laporan Bulanan</h1>
            <p class="text-on-surface-variant mt-1">Ringkasan pendapatan dan pengeluaran per bulan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="?page=laporan&action=exportPdf&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
               class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-600 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                Export PDF
            </a>
            <a href="?page=laporan&action=pendapatan" class="flex items-center gap-2 bg-surface-container border border-outline-variant/50 text-on-surface px-4 py-2 rounded-xl text-sm font-bold hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                Manajemen Pendapatan
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl"><?= htmlspecialchars($_SESSION['success_message']) ?><?php unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl"><?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <!-- Month Selector -->
    <div class="glass-panel p-5 rounded-2xl flex flex-wrap items-center gap-4">
        <span class="material-symbols-outlined text-primary">calendar_month</span>
        <span class="font-bold text-on-surface">Pilih Bulan:</span>
        <form method="GET" action="" class="flex flex-wrap gap-3 items-center">
            <input type="hidden" name="page" value="laporan">
            <select name="bulan" class="px-4 py-2 rounded-xl border border-outline-variant/50 bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m === $bulan ? 'selected' : '' ?>><?= $namaBulan[$m] ?></option>
                <?php endfor; ?>
            </select>
            <select name="tahun" class="px-4 py-2 rounded-xl border border-outline-variant/50 bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                <?php for ($y = (int)date('Y'); $y >= 2023; $y--): ?>
                    <option value="<?= $y ?>" <?= $y === $tahun ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-primary-container transition-colors">Tampilkan</button>
        </form>

        <?php if (!empty($availableMonths)): ?>
        <div class="flex flex-wrap gap-2 ml-auto">
            <?php foreach (array_slice($availableMonths, 0, 6) as $m): ?>
                <a href="?page=laporan&bulan=<?= $m['month'] ?>&tahun=<?= $m['year'] ?>"
                   class="text-xs px-3 py-1 rounded-full border <?= ($m['month'] === $bulan && $m['year'] === $tahun) ? 'bg-primary text-white border-primary' : 'border-outline-variant/50 text-on-surface-variant hover:bg-surface-container' ?> transition-colors">
                    <?= $namaBulan[$m['month']] ?> <?= $m['year'] ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-2">
            <div class="flex items-center gap-2 text-green-600">
                <span class="material-symbols-outlined">trending_up</span>
                <span class="text-sm font-bold uppercase tracking-wider">Total Pendapatan</span>
            </div>
            <p class="text-2xl font-black text-on-surface">Rp. <?= number_format($report['total_pendapatan'], 0, ',', '.') ?></p>
            <p class="text-xs text-on-surface-variant"><?= count($report['orders']) ?> order · omset Rp. <?= number_format($report['total_omset'], 0, ',', '.') ?></p>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-outline-variant/30 space-y-2">
            <div class="flex items-center gap-2 text-red-500">
                <span class="material-symbols-outlined">trending_down</span>
                <span class="text-sm font-bold uppercase tracking-wider">Total Pengeluaran</span>
            </div>
            <p class="text-2xl font-black text-on-surface">Rp. <?= number_format($report['total_pengeluaran'], 0, ',', '.') ?></p>
            <p class="text-xs text-on-surface-variant"><?= count($report['pengeluaran']) ?> item pengeluaran</p>
        </div>
        <div class="p-6 rounded-2xl space-y-2 <?= $report['keuntungan_bersih'] >= 0 ? 'bg-primary text-white' : 'bg-red-500 text-white' ?>">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined">account_balance_wallet</span>
                <span class="text-sm font-bold uppercase tracking-wider opacity-80">Keuntungan Bersih</span>
            </div>
            <p class="text-3xl font-black">Rp. <?= number_format(abs($report['keuntungan_bersih']), 0, ',', '.') ?></p>
            <p class="text-xs opacity-70"><?= $report['keuntungan_bersih'] >= 0 ? 'Surplus' : 'Defisit' ?> <?= $currentMonthLabel ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Pendapatan dari Order -->
        <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600">payments</span>
                    <h2 class="font-bold text-on-surface">Pendapatan — <?= $currentMonthLabel ?></h2>
                </div>
                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold"><?= count($report['orders']) ?> order</span>
            </div>
            <?php if (empty($report['orders'])): ?>
                <div class="p-8 text-center text-on-surface-variant text-sm">Belum ada order di bulan ini.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">No. Order</th>
                                <th class="px-4 py-3 text-left">Pemesan</th>
                                <th class="px-4 py-3 text-right">Margin</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            <?php foreach ($report['orders'] as $order):
                                $items  = json_decode($order->getListItemOrder(), true) ?? [];
                                $margin = array_reduce($items, function($carry, $item) {
                                    return $carry + (((float)($item['price'] ?? 0) - (float)($item['modal'] ?? 0)) * (int)($item['qty'] ?? 1));
                                }, 0.0);
                            ?>
                            <tr class="hover:bg-surface-container/50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-primary font-bold"><?= htmlspecialchars($order->getOrderNumber()) ?></td>
                                <td class="px-4 py-3 text-on-surface"><?= htmlspecialchars($order->getNamaPemesan()) ?></td>
                                <td class="px-4 py-3 text-right font-bold text-on-surface">Rp. <?= number_format($margin, 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs px-2 py-1 rounded-full font-bold
                                        <?= $order->getOrderStatus() === 'selesai' ? 'bg-green-100 text-green-700' : ($order->getOrderStatus() === 'dibatalkan' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') ?>">
                                        <?= htmlspecialchars($order->getOrderStatus()) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-t-2 border-outline-variant/40">
                            <tr class="bg-surface-container-low">
                                <td colspan="2" class="px-4 py-3 font-bold text-on-surface">Total Margin</td>
                                <td class="px-4 py-3 text-right font-black text-green-600">Rp. <?= number_format($report['total_pendapatan'], 0, ',', '.') ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pengeluaran -->
        <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">shopping_bag</span>
                    <h2 class="font-bold text-on-surface">Pengeluaran — <?= $currentMonthLabel ?></h2>
                </div>
                <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold"><?= count($report['pengeluaran']) ?> item</span>
            </div>

            <!-- Form tambah pengeluaran -->
            <div class="p-5 border-b border-outline-variant/20 bg-surface-container/30">
                <form action="?page=laporan&action=storePengeluaran" method="POST" class="space-y-3">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                    <input type="hidden" name="redirect_bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="redirect_tahun" value="<?= $tahun ?>">
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">+ Tambah Pengeluaran</p>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="tanggal" required
                               value="<?= date('Y-m') . sprintf('-%02d', min((int)date('d'), cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun))) ?>"
                               class="col-span-2 sm:col-span-1 px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                        <div class="col-span-2 sm:col-span-1">
                            <input type="number" name="jumlah" id="inp-jumlah-pengeluaran" placeholder="Jumlah (Rp)" min="1" step="any" required
                                   class="w-full px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                            <p id="hint-jumlah-pengeluaran" class="text-[10px] text-primary font-mono font-bold mt-0.5 pl-1"></p>
                        </div>
                        <input type="text" name="keterangan" placeholder="Keterangan pengeluaran..." required
                               class="col-span-2 px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface text-sm outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-xl text-sm font-bold hover:bg-red-600 transition-colors">Simpan Pengeluaran</button>
                </form>
            </div>

            <?php if (empty($report['pengeluaran'])): ?>
                <div class="p-8 text-center text-on-surface-variant text-sm">Belum ada pengeluaran di bulan ini.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            <?php foreach ($report['pengeluaran'] as $p): ?>
                            <tr class="hover:bg-surface-container/50 transition-colors">
                                <td class="px-4 py-3 text-on-surface-variant text-xs"><?= $p->getTanggal()->format('d M Y') ?></td>
                                <td class="px-4 py-3 text-on-surface"><?= htmlspecialchars($p->getKeterangan()) ?></td>
                                <td class="px-4 py-3 text-right font-bold text-red-500">Rp. <?= number_format((float)$p->getJumlah(), 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1">
                                        <a href="?page=laporan&action=editPengeluaran&id=<?= $p->getId() ?>"
                                           class="p-1.5 rounded-lg bg-surface-container hover:bg-outline-variant/30 transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </a>
                                        <form method="POST" action="?page=laporan&action=deletePengeluaran&id=<?= $p->getId() ?>"
                                              onsubmit="return confirm('Hapus pengeluaran ini?')">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                            <input type="hidden" name="redirect_bulan" value="<?= $bulan ?>">
                                            <input type="hidden" name="redirect_tahun" value="<?= $tahun ?>">
                                            <button type="submit" class="p-1.5 rounded-lg bg-surface-container hover:bg-red-100 text-red-500 transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-t-2 border-outline-variant/40">
                            <tr class="bg-surface-container-low">
                                <td colspan="2" class="px-4 py-3 font-bold text-on-surface">Total</td>
                                <td class="px-4 py-3 text-right font-black text-red-500">Rp. <?= number_format($report['total_pengeluaran'], 0, ',', '.') ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
(function() {
    const el   = document.getElementById('inp-jumlah-pengeluaran');
    const hint = document.getElementById('hint-jumlah-pengeluaran');
    if (!el) return;
    el.addEventListener('input', () => {
        const v = parseFloat(el.value) || 0;
        hint.textContent = v > 0 ? 'Rp. ' + new Intl.NumberFormat('id-ID').format(v) : '';
    });
}());
</script>
