<?php
$isDraft    = $sesi->getStatus() === 'draft';
$totalBiaya = array_sum(array_map(fn($k) => $k->getJumlah(), $komponen));
$fmt = fn($n) => 'Rp. ' . number_format((float)$n, 0, ',', '.');
?>
<div class="px-4 md:px-margin-desktop py-10 max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="?page=sesi" class="p-2 rounded-xl bg-surface-container border border-outline-variant hover:border-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-2xl font-black text-on-surface"><?= htmlspecialchars($sesi->getNamaSesi()) ?></h1>
                <span class="text-xs font-bold px-3 py-1 rounded-full <?= $isDraft ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' ?>">
                    <?= $isDraft ? 'Draft' : 'Selesai' ?>
                </span>
            </div>
            <p class="text-on-surface-variant text-sm"><?= $sesi->getTanggal()->format('d F Y') ?>
                · Distribusi <?= $sesi->getPersenProporsional() ?>% proporsional / <?= 100 - $sesi->getPersenProporsional() ?>% rata
            </p>
        </div>
    </div>

    <!-- Biaya Tetap + BEP -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-panel p-5 rounded-2xl border border-outline-variant/30 space-y-3">
            <h3 class="font-bold text-sm uppercase tracking-wider text-on-surface-variant">Komponen Biaya Tetap</h3>
            <?php foreach ($komponen as $k): ?>
            <div class="flex justify-between text-sm">
                <span class="text-on-surface-variant"><?= htmlspecialchars($k->getNamaKomponen()) ?></span>
                <strong class="text-red-500"><?= $fmt($k->getJumlah()) ?></strong>
            </div>
            <?php endforeach; ?>
            <div class="flex justify-between text-sm border-t border-outline-variant/30 pt-3">
                <span class="font-bold text-on-surface">Total Biaya Tetap</span>
                <strong class="text-red-500"><?= $fmt($totalBiaya) ?></strong>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-2xl border border-outline-variant/30 space-y-3">
            <h3 class="font-bold text-sm uppercase tracking-wider text-on-surface-variant">Ringkasan Sesi</h3>
            <div class="flex justify-between text-sm">
                <span class="text-on-surface-variant">Jumlah Produk</span>
                <strong><?= count($produkList) ?> produk</strong>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-on-surface-variant">Total Estimasi Order</span>
                <strong><?= array_sum(array_map(fn($p) => $p->getEstimasiQty(), $produkList)) ?> item</strong>
            </div>
            <div class="flex justify-between text-sm border-t border-outline-variant/30 pt-3">
                <span class="font-bold text-on-surface">Break-Even Point</span>
                <strong class="text-primary text-lg"><?= $bep !== null ? ceil($bep) . ' item' : 'N/A' ?></strong>
            </div>
        </div>
    </div>

    <!-- Tabel Kalkulasi Estimasi -->
    <?php if (!empty($kalkulasi)): ?>
    <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/30">
            <h3 class="font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">calculate</span>
                Kalkulasi Sugesti Harga (Estimasi)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-container-low text-xs uppercase tracking-wider text-on-surface-variant">
                    <tr>
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-right">Est. Qty</th>
                        <th class="px-4 py-3 text-right">HPP</th>
                        <th class="px-4 py-3 text-right">Beban Trip/item</th>
                        <th class="px-4 py-3 text-right">Sugesti Harga</th>
                        <th class="px-4 py-3 text-right">Harga Katalog</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    <?php foreach ($kalkulasi as $i => $k):
                        $p = $produkList[$i]; ?>
                    <tr class="hover:bg-surface-container/40 transition-colors">
                        <td class="px-4 py-3 font-bold text-on-surface"><?= htmlspecialchars($p->getNamaSnapshot()) ?></td>
                        <td class="px-4 py-3 text-right text-on-surface-variant"><?= $p->getEstimasiQty() ?></td>
                        <td class="px-4 py-3 text-right text-on-surface-variant font-mono"><?= $fmt($p->getHppPerPcsSnapshot()) ?></td>
                        <td class="px-4 py-3 text-right text-red-500 font-mono font-bold"><?= $fmt($k['beban_per_item']) ?></td>
                        <td class="px-4 py-3 text-right text-primary font-mono font-black"><?= $fmt($k['sugesti_harga']) ?></td>
                        <td class="px-4 py-3 text-right font-mono <?= $k['is_boncos'] ? 'text-red-500 line-through' : 'text-on-surface' ?>"><?= $fmt($p->getHargaJualSnapshot()) ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($k['is_boncos']): ?>
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[12px]">warning</span>
                                    Boncos <?= $fmt(abs($k['selisih'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[12px]">check_circle</span>
                                    Aman +<?= $fmt($k['selisih']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hasil Aktual Pasca-Trip -->
    <?php if (!$isDraft && $labaAktual !== null): ?>
    <div class="rounded-2xl border-2 <?= $labaAktual['total_laba'] >= 0 ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' ?> overflow-hidden">
        <div class="px-5 py-4 border-b <?= $labaAktual['total_laba'] >= 0 ? 'border-green-200' : 'border-red-200' ?>">
            <h3 class="font-bold <?= $labaAktual['total_laba'] >= 0 ? 'text-green-700' : 'text-red-600' ?> flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">analytics</span>
                Hasil Aktual Pasca-Trip
                <span class="text-2xl font-black ml-auto">
                    <?= $labaAktual['total_laba'] >= 0 ? '+' : '' ?><?= $fmt($labaAktual['total_laba']) ?>
                </span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase tracking-wider text-on-surface-variant">
                    <tr>
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-right">Aktual Qty</th>
                        <th class="px-4 py-3 text-right">True Cost/item</th>
                        <th class="px-4 py-3 text-right">Laba/item</th>
                        <th class="px-4 py-3 text-right">Total Laba</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    <?php foreach ($labaAktual['rows'] as $r): ?>
                    <tr class="hover:bg-white/50 transition-colors">
                        <td class="px-4 py-3 font-bold text-on-surface"><?= htmlspecialchars($r['nama']) ?></td>
                        <td class="px-4 py-3 text-right"><?= $r['qty'] ?></td>
                        <td class="px-4 py-3 text-right font-mono text-red-500"><?= $fmt($r['trueCost']) ?></td>
                        <td class="px-4 py-3 text-right font-mono font-bold <?= $r['laba_per_item'] >= 0 ? 'text-green-700' : 'text-red-600' ?>">
                            <?= ($r['laba_per_item'] >= 0 ? '+' : '') . $fmt($r['laba_per_item']) ?>
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-black <?= $r['laba_total'] >= 0 ? 'text-green-700' : 'text-red-600' ?>">
                            <?= ($r['laba_total'] >= 0 ? '+' : '') . $fmt($r['laba_total']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif ($isDraft): ?>
    <div class="rounded-2xl p-5 border border-yellow-200 bg-yellow-50 flex items-center gap-3">
        <span class="material-symbols-outlined text-yellow-500">info</span>
        <p class="text-sm text-yellow-700">Sesi masih <strong>Draft</strong>. Tutup sesi setelah perjalanan selesai untuk melihat laba aktual dan mencatat biaya ke pengeluaran.</p>
    </div>
    <?php endif; ?>

    <?php if ($sesi->getCatatan()): ?>
    <div class="glass-panel p-5 rounded-2xl border border-outline-variant/30">
        <h3 class="font-bold text-sm uppercase tracking-wider text-on-surface-variant mb-2">Catatan</h3>
        <p class="text-sm text-on-surface"><?= nl2br(htmlspecialchars($sesi->getCatatan())) ?></p>
    </div>
    <?php endif; ?>
</div>
