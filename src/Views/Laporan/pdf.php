<?php
// ── Pre-compute stats ─────────────────────────────────────────────────────────
$namaBulanArr = ['','Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];

$totalOmset      = $report['total_omset'];
$totalPendapatan = $report['total_pendapatan'];   // margin bersih
$totalPengeluaran= $report['total_pengeluaran'];
$labaBersih      = $report['keuntungan_bersih'];
$totalModal      = $totalOmset - $totalPendapatan;

$marginPct = $totalOmset > 0 ? round(($totalPendapatan / $totalOmset) * 100, 1) : 0;
$labaPct   = $totalOmset > 0 ? round(($labaBersih / $totalOmset) * 100, 1) : 0;

$orders      = $report['orders'];
$pengeluaran = $report['pengeluaran'];
$jumlahOrder = count($orders);

$orderSelesai    = count(array_filter($orders, fn($o) => strtolower($o->getOrderStatus()) === 'selesai'));
$orderDibatalkan = count(array_filter($orders, fn($o) => strtolower($o->getOrderStatus()) === 'dibatalkan'));
$orderProses     = $jumlahOrder - $orderSelesai - $orderDibatalkan;

// Aggregate items across all orders
$itemMap = [];
foreach ($orders as $o) {
    $items = json_decode($o->getListItemOrder(), true) ?? [];
    foreach ($items as $item) {
        $name  = $item['name'] ?? 'Unknown';
        $qty   = (int)($item['qty'] ?? 1);
        $price = (float)($item['price'] ?? 0);
        $modal = (float)($item['modal'] ?? 0);
        if (!isset($itemMap[$name])) $itemMap[$name] = ['qty' => 0, 'omset' => 0.0, 'margin' => 0.0];
        $itemMap[$name]['qty']    += $qty;
        $itemMap[$name]['omset']  += $price * $qty;
        $itemMap[$name]['margin'] += ($price - $modal) * $qty;
    }
}
uasort($itemMap, fn($a, $b) => $b['omset'] <=> $a['omset']);
$topItems = array_slice($itemMap, 0, 5, true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<style>
/* ── Reset ── */
* { margin: 0; padding: 0; }
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10.5px;
    color: #1e1b4b;
    background: #fff;
    line-height: 1.5;
}

/* ── Page wrapper ── */
.page { padding: 0; }

/* ── Cover band ── */
.cover-band {
    background: #312e81;
    color: #fff;
    padding: 0;
}
.cover-inner {
    padding: 26px 32px 20px;
}
.cover-top {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 14px;
}
.cover-top td { vertical-align: top; }
.brand-name {
    font-size: 17px;
    font-weight: bold;
    letter-spacing: 0.3px;
    color: #c7d2fe;
}
.brand-tagline {
    font-size: 9px;
    color: #a5b4fc;
    margin-top: 2px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}
.doc-badge {
    background: #4338ca;
    border: 1px solid #6366f1;
    color: #e0e7ff;
    padding: 4px 12px;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-align: center;
}
.cover-title {
    font-size: 22px;
    font-weight: bold;
    color: #fff;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}
.cover-period {
    font-size: 13px;
    color: #a5b4fc;
    font-weight: bold;
}
.cover-divider {
    height: 3px;
    background: #4f46e5;
    border-top: 1px solid #6366f1;
}

/* ── Meta strip ── */
.meta-strip {
    background: #f5f3ff;
    border-bottom: 1px solid #ddd6fe;
    padding: 8px 32px;
}
.meta-strip table { width: 100%; border-collapse: collapse; }
.meta-strip td { font-size: 9px; color: #5b21b6; padding: 0 8px 0 0; vertical-align: middle; }
.meta-key { font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #7c3aed; }

/* ── Body content ── */
.body { padding: 22px 32px; }

/* ── Section ── */
.section { margin-bottom: 24px; }
.section-head {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}
.section-head td { vertical-align: middle; }
.section-num {
    background: #4f46e5;
    color: #fff;
    font-size: 9px;
    font-weight: bold;
    width: 20px;
    text-align: center;
    padding: 3px 0;
}
.section-label {
    background: #eef2ff;
    padding: 4px 10px;
    font-size: 10px;
    font-weight: bold;
    color: #3730a3;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    border-bottom: 2px solid #6366f1;
}

/* ── KPI grid (table-based) ── */
.kpi-table { width: 100%; border-collapse: separate; border-spacing: 6px; }
.kpi-cell {
    padding: 13px 14px;
    vertical-align: top;
    border-radius: 6px;
}
.kpi-green  { background: #f0fdf4; border: 1px solid #86efac; }
.kpi-orange { background: #fff7ed; border: 1px solid #fdba74; }
.kpi-red    { background: #fff1f2; border: 1px solid #fca5a5; }
.kpi-indigo { background: #eef2ff; border: 1px solid #a5b4fc; }
.kpi-label { font-size: 8.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; margin-bottom: 5px; }
.kpi-value { font-size: 15px; font-weight: bold; color: #111827; }
.kpi-sub   { font-size: 8.5px; color: #6b7280; margin-top: 3px; }
.kpi-pct   { font-size: 10px; font-weight: bold; }
.txt-green  { color: #15803d; }
.txt-orange { color: #c2410c; }
.txt-red    { color: #b91c1c; }
.txt-indigo { color: #3730a3; }

/* ── Waterfall income statement ── */
.waterfall { width: 100%; border-collapse: collapse; font-size: 10px; }
.waterfall td { padding: 7px 12px; vertical-align: middle; }
.wf-label    { color: #374151; }
.wf-value    { text-align: right; font-weight: bold; color: #111827; white-space: nowrap; }
.wf-pct      { text-align: right; color: #6b7280; font-size: 8.5px; width: 55px; }
.wf-bar-cell { width: 130px; padding: 7px 8px; }
.wf-bar-bg   { background: #f3f4f6; height: 8px; border-radius: 4px; }
.wf-bar-fill { height: 8px; border-radius: 4px; }
.bar-green  { background: #22c55e; }
.bar-orange { background: #f97316; }
.bar-red    { background: #ef4444; }
.bar-indigo { background: #6366f1; }
.wf-sep td  { border-top: 1px solid #e5e7eb; padding: 2px 0; }
.wf-total td { border-top: 2px solid #6366f1; background: #eef2ff; font-weight: bold; }
.wf-stripe  { background: #fafafa; }

/* ── Data tables ── */
.data-table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
.data-table th {
    background: #312e81;
    color: #e0e7ff;
    padding: 7px 10px;
    text-align: left;
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.data-table th.r  { text-align: right; }
.data-table th.c  { text-align: center; }
.data-table td { padding: 6px 10px; border-bottom: 1px solid #ede9fe; color: #374151; vertical-align: top; }
.data-table td.r  { text-align: right; }
.data-table td.c  { text-align: center; }
.data-table tr.even td { background: #faf9ff; }
.data-table tfoot td {
    background: #eef2ff;
    border-top: 2px solid #6366f1;
    padding: 7px 10px;
    font-weight: bold;
}

.mono { font-family: DejaVu Sans Mono, monospace; font-size: 8.5px; color: #4338ca; }

/* ── Badge ── */
.badge { display: inline; padding: 1px 7px; border-radius: 8px; font-size: 8px; font-weight: bold; }
.badge-selesai    { background: #dcfce7; color: #166534; }
.badge-dibatalkan { background: #fee2e2; color: #991b1b; }
.badge-proses     { background: #fef9c3; color: #854d0e; }

/* ── Item sub-table inside order row ── */
.item-list { width: 100%; border-collapse: collapse; margin-top: 4px; }
.item-list td { padding: 2px 6px; font-size: 8.5px; color: #4b5563; border: none; background: transparent; }
.item-list td.ir { text-align: right; }
.item-dot { color: #a5b4fc; font-size: 10px; }

/* ── Status summary chips ── */
.status-row { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.status-chip { padding: 6px 10px; border-radius: 6px; text-align: center; vertical-align: middle; }
.chip-selesai    { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.chip-proses     { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
.chip-dibatalkan { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.chip-num  { font-size: 18px; font-weight: bold; }
.chip-lbl  { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }

/* ── Final recap box ── */
.recap-box {
    border: 2px solid <?= $labaBersih >= 0 ? '#6366f1' : '#ef4444' ?>;
    background: <?= $labaBersih >= 0 ? '#eef2ff' : '#fff1f2' ?>;
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
}
.recap-table { width: 100%; border-collapse: collapse; }
.recap-table td { padding: 4px 0; font-size: 10px; color: #374151; }
.recap-table td.rv { text-align: right; font-weight: bold; }
.recap-sep td { border-top: 1px dashed #c4b5fd; padding: 3px 0; }
.recap-final td { border-top: 2px solid <?= $labaBersih >= 0 ? '#4f46e5' : '#dc2626' ?>; padding-top: 6px; font-size: 12px; font-weight: bold; }
.recap-final .rv { color: <?= $labaBersih >= 0 ? '#3730a3' : '#b91c1c' ?>; font-size: 15px; }
.recap-verdict {
    font-size: 10px;
    font-weight: bold;
    color: <?= $labaBersih >= 0 ? '#3730a3' : '#b91c1c' ?>;
    margin-top: 8px;
    text-align: center;
    letter-spacing: 0.3px;
}

/* ── Sign area ── */
.sign-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
.sign-cell { vertical-align: bottom; text-align: center; padding: 0 10px; }
.sign-line { border-top: 1px solid #9ca3af; margin-top: 40px; width: 80%; margin-left: auto; margin-right: auto; }
.sign-label { font-size: 9px; color: #6b7280; margin-top: 4px; }

/* ── Footer ── */
.page-footer {
    margin-top: 28px;
    padding: 10px 32px;
    border-top: 2px solid #312e81;
    background: #f5f3ff;
}
.footer-table { width: 100%; border-collapse: collapse; }
.footer-table td { font-size: 8.5px; color: #5b21b6; vertical-align: middle; }
.footer-right { text-align: right; }
.txt-muted { color: #9ca3af; }
</style>
</head>
<body>
<div class="page">

<!-- ════════════════════════════════════════════════════════
     COVER BAND
════════════════════════════════════════════════════════ -->
<div class="cover-band">
    <div class="cover-inner">
        <table class="cover-top">
            <tr>
                <td style="width:60%;">
                    <div class="brand-name">&#9654; Jastip Arunga</div>
                    <div class="brand-tagline">Personal Shopper &middot; Nusantara</div>
                </td>
                <td style="width:40%; text-align:right;">
                    <div class="doc-badge">Laporan Keuangan Bulanan</div>
                </td>
            </tr>
        </table>
        <div class="cover-title">Laporan <?= htmlspecialchars($currentMonthLabel) ?></div>
        <div class="cover-period">
            Periode: 1 <?= htmlspecialchars($currentMonthLabel) ?> &mdash;
            <?= date('d', mktime(0,0,0,$bulan+1,0,$tahun)) ?> <?= htmlspecialchars($currentMonthLabel) ?>
        </div>
    </div>
    <div class="cover-divider"></div>
</div>

<!-- Meta strip -->
<div class="meta-strip">
    <table>
        <tr>
            <td><span class="meta-key">Diterbitkan:</span> <?= date('d F Y, H:i') ?> WIB</td>
            <td><span class="meta-key">Jumlah Order:</span> <?= $jumlahOrder ?></td>
            <td><span class="meta-key">Jumlah Pengeluaran:</span> <?= count($pengeluaran) ?> item</td>
            <td style="text-align:right;"><span class="meta-key">Status:</span>
                <?php if($labaBersih >= 0): ?>
                    <span style="color:#15803d; font-weight:bold;">&#10003; SURPLUS</span>
                <?php else: ?>
                    <span style="color:#b91c1c; font-weight:bold;">&#9650; DEFISIT</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

<!-- ════════════════════════════════════════════════════════
     BODY
════════════════════════════════════════════════════════ -->
<div class="body">

<!-- ── 1. KPI Summary ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num">01</td>
            <td class="section-label">Ringkasan Eksekutif</td>
        </tr>
    </table>

    <table class="kpi-table">
        <tr>
            <td class="kpi-cell kpi-orange" style="width:25%;">
                <div class="kpi-label">Total Omset</div>
                <div class="kpi-value txt-orange">Rp. <?= number_format($totalOmset, 0, ',', '.') ?></div>
                <div class="kpi-sub"><?= $jumlahOrder ?> order bulan ini</div>
            </td>
            <td style="width:2%;"></td>
            <td class="kpi-cell kpi-green" style="width:25%;">
                <div class="kpi-label">Total Margin (Pendapatan)</div>
                <div class="kpi-value txt-green">Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></div>
                <div class="kpi-sub">
                    <span class="kpi-pct txt-green"><?= $marginPct ?>%</span> dari omset
                </div>
            </td>
            <td style="width:2%;"></td>
            <td class="kpi-cell kpi-red" style="width:25%;">
                <div class="kpi-label">Total Pengeluaran</div>
                <div class="kpi-value txt-red">Rp. <?= number_format($totalPengeluaran, 0, ',', '.') ?></div>
                <div class="kpi-sub"><?= count($pengeluaran) ?> item operasional</div>
            </td>
            <td style="width:2%;"></td>
            <td class="kpi-cell kpi-indigo" style="width:25%;">
                <div class="kpi-label">Laba Bersih</div>
                <div class="kpi-value <?= $labaBersih >= 0 ? 'txt-indigo' : 'txt-red' ?>">
                    <?= $labaBersih < 0 ? '&minus;' : '' ?>Rp. <?= number_format(abs($labaBersih), 0, ',', '.') ?>
                </div>
                <div class="kpi-sub">
                    <span class="kpi-pct <?= $labaBersih >= 0 ? 'txt-green' : 'txt-red' ?>"><?= $labaPct ?>%</span>
                    dari omset &mdash; <?= $labaBersih >= 0 ? 'Surplus' : 'Defisit' ?>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ── 2. Analisis Keuangan (Waterfall) ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num">02</td>
            <td class="section-label">Analisis Arus Keuangan</td>
        </tr>
    </table>

    <?php
    $wfItems = [
        ['Omset Kotor (Total Penjualan)',       $totalOmset,       $totalOmset, 'bar-orange', 'txt-orange'],
        ['(-) Modal / HPP Barang',              -$totalModal,       $totalModal, 'bar-red',    'txt-red'],
        ['(=) Pendapatan Bersih (Margin)',       $totalPendapatan,  $totalOmset, 'bar-green',  'txt-green'],
        ['(-) Biaya Operasional (Pengeluaran)', -$totalPengeluaran, $totalOmset, 'bar-red',    'txt-red'],
    ];
    $maxBar = $totalOmset > 0 ? $totalOmset : 1;
    ?>
    <table class="waterfall">
        <thead>
            <tr style="background:#f3f4f6;">
                <td style="padding:5px 12px; font-size:8.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; width:42%;">Komponen</td>
                <td style="padding:5px 10px; font-size:8.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; text-align:right; width:25%;">Nilai (Rp)</td>
                <td style="padding:5px 8px; font-size:8.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; text-align:right; width:8%;">%</td>
                <td style="padding:5px 8px; font-size:8.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; width:25%;">Proporsi</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($wfItems as $i => $row):
            $pct = $totalOmset > 0 ? round(abs($row[1]) / $totalOmset * 100, 1) : 0;
            $barW = $maxBar > 0 ? round(abs($row[2]) / $maxBar * 100) : 0;
        ?>
        <tr class="<?= $i % 2 === 1 ? 'wf-stripe' : '' ?>">
            <td class="wf-label"><?= $row[0] ?></td>
            <td class="wf-value <?= $row[4] ?>">
                <?= $row[1] < 0 ? '&minus; ' : '' ?>Rp. <?= number_format(abs($row[1]), 0, ',', '.') ?>
            </td>
            <td class="wf-pct"><?= $pct ?>%</td>
            <td class="wf-bar-cell">
                <div class="wf-bar-bg">
                    <div class="wf-bar-fill <?= $row[3] ?>" style="width:<?= $barW ?>%;"></div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="wf-total">
                <td style="padding:8px 12px; font-weight:bold; font-size:11px;">(=) Laba Bersih</td>
                <td style="padding:8px 10px; text-align:right; font-size:12px; font-weight:bold; color:<?= $labaBersih >= 0 ? '#3730a3' : '#b91c1c' ?>;">
                    <?= $labaBersih < 0 ? '&minus; ' : '' ?>Rp. <?= number_format(abs($labaBersih), 0, ',', '.') ?>
                </td>
                <td style="padding:8px 10px; text-align:right; font-weight:bold; color:<?= $labaBersih >= 0 ? '#15803d' : '#b91c1c' ?>;"><?= $labaPct ?>%</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- ── 3. Status Order ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num">03</td>
            <td class="section-label">Distribusi Status Order</td>
        </tr>
    </table>

    <table class="status-row">
        <tr>
            <td class="status-chip chip-selesai" style="width:33%;">
                <div class="chip-num"><?= $orderSelesai ?></div>
                <div class="chip-lbl">&#10003; Selesai</div>
            </td>
            <td style="width:2%;"></td>
            <td class="status-chip chip-proses" style="width:33%;">
                <div class="chip-num"><?= $orderProses ?></div>
                <div class="chip-lbl">&#9654; Dalam Proses</div>
            </td>
            <td style="width:2%;"></td>
            <td class="status-chip chip-dibatalkan" style="width:33%;">
                <div class="chip-num"><?= $orderDibatalkan ?></div>
                <div class="chip-lbl">&#10007; Dibatalkan</div>
            </td>
        </tr>
    </table>
</div>

<!-- ── 4. Detail Order ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num">04</td>
            <td class="section-label">Detail Transaksi Order</td>
        </tr>
    </table>

    <?php if (empty($orders)): ?>
        <p style="color:#9ca3af; font-size:10px; padding:10px 0; text-align:center;">Tidak ada order pada periode ini.</p>
    <?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:17%;">No. Order</th>
                <th style="width:18%;">Pemesan</th>
                <th style="width:15%;">Item Pesanan</th>
                <th style="width:10%;">Tgl Order</th>
                <th class="r" style="width:15%;">Omset</th>
                <th class="r" style="width:13%;">Margin</th>
                <th class="c" style="width:12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $idx => $order):
                $items  = json_decode($order->getListItemOrder(), true) ?? [];
                $margin = array_reduce($items, fn($c, $i) =>
                    $c + (((float)($i['price']??0) - (float)($i['modal']??0)) * (int)($i['qty']??1)), 0.0);
                $st = strtolower($order->getOrderStatus());
                $badgeClass = $st === 'selesai' ? 'badge-selesai' : ($st === 'dibatalkan' ? 'badge-dibatalkan' : 'badge-proses');
            ?>
            <tr class="<?= $idx % 2 === 1 ? 'even' : '' ?>">
                <td><span class="mono"><?= htmlspecialchars($order->getOrderNumber()) ?></span></td>
                <td>
                    <?= htmlspecialchars($order->getNamaPemesan()) ?>
                    <?php if ($order->getInstagramUserNamePemesan()): ?>
                        <br><span style="color:#9ca3af; font-size:8px;">@<?= htmlspecialchars($order->getInstagramUserNamePemesan()) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($items)): ?>
                    <table class="item-list">
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="width:8px;"><span class="item-dot">&bull;</span></td>
                            <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                            <td class="ir" style="color:#6b7280;">x<?= (int)($item['qty']??1) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php else: ?>
                        <span style="color:#d1d5db;">—</span>
                    <?php endif; ?>
                </td>
                <td><?= $order->getCreatedAt()->format('d M Y') ?></td>
                <td class="r">Rp. <?= number_format((float)$order->getSubTotal(), 0, ',', '.') ?></td>
                <td class="r" style="color:#15803d; font-weight:bold;">Rp. <?= number_format($margin, 0, ',', '.') ?></td>
                <td class="c"><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($order->getOrderStatus()) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="font-weight:bold;">Total</td>
                <td class="r" style="color:#c2410c;">Rp. <?= number_format($totalOmset, 0, ',', '.') ?></td>
                <td class="r" style="color:#15803d;">Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
</div>

<!-- ── 5. Top Produk ── -->
<?php if (!empty($topItems)): ?>
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num">05</td>
            <td class="section-label">5 Produk Teratas Bulan Ini</td>
        </tr>
    </table>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:45%;">Nama Produk / Barang Jastip</th>
                <th class="r" style="width:15%;">Qty Terjual</th>
                <th class="r" style="width:20%;">Total Omset</th>
                <th class="r" style="width:15%;">Total Margin</th>
            </tr>
        </thead>
        <tbody>
            <?php $rank = 1; foreach ($topItems as $name => $stat): ?>
            <tr class="<?= $rank % 2 === 0 ? 'even' : '' ?>">
                <td class="c" style="font-weight:bold; color:#6366f1;"><?= $rank ?></td>
                <td><?= htmlspecialchars($name) ?></td>
                <td class="r"><?= $stat['qty'] ?> pcs</td>
                <td class="r">Rp. <?= number_format($stat['omset'], 0, ',', '.') ?></td>
                <td class="r" style="color:#15803d; font-weight:bold;">Rp. <?= number_format($stat['margin'], 0, ',', '.') ?></td>
            </tr>
            <?php $rank++; endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- ── 6. Detail Pengeluaran ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num"><?= !empty($topItems) ? '06' : '05' ?></td>
            <td class="section-label">Rincian Pengeluaran Operasional</td>
        </tr>
    </table>

    <?php if (empty($pengeluaran)): ?>
        <p style="color:#9ca3af; font-size:10px; padding:10px 0; text-align:center;">Tidak ada pengeluaran pada periode ini.</p>
    <?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:20%;">Tanggal</th>
                <th style="width:55%;">Keterangan</th>
                <th class="r" style="width:20%;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pengeluaran as $idx => $p): ?>
            <tr class="<?= $idx % 2 === 1 ? 'even' : '' ?>">
                <td class="c" style="color:#9ca3af;"><?= $idx + 1 ?></td>
                <td><?= $p->getTanggal()->format('d F Y') ?></td>
                <td><?= htmlspecialchars($p->getKeterangan()) ?></td>
                <td class="r" style="color:#b91c1c; font-weight:bold;">Rp. <?= number_format((float)$p->getJumlah(), 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="font-weight:bold;">Total Pengeluaran</td>
                <td class="r" style="color:#b91c1c;">Rp. <?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
</div>

<!-- ── 7. Rekap Akhir ── -->
<div class="section">
    <table class="section-head">
        <tr>
            <td class="section-num"><?= !empty($topItems) ? '07' : '06' ?></td>
            <td class="section-label">Rekap Keuangan Akhir</td>
        </tr>
    </table>

    <div class="recap-box">
        <table class="recap-table">
            <tr>
                <td>Omset Kotor</td>
                <td class="rv">Rp. <?= number_format($totalOmset, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>(-) Modal / HPP Barang</td>
                <td class="rv" style="color:#b91c1c;">&minus; Rp. <?= number_format($totalModal, 0, ',', '.') ?></td>
            </tr>
            <tr class="recap-sep"><td colspan="2"></td></tr>
            <tr>
                <td>Pendapatan Bersih (Margin)</td>
                <td class="rv" style="color:#15803d;">Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>(-) Biaya Operasional</td>
                <td class="rv" style="color:#b91c1c;">&minus; Rp. <?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
            </tr>
            <tr class="recap-final">
                <td>Laba Bersih <?= htmlspecialchars($currentMonthLabel) ?></td>
                <td class="rv">
                    <?= $labaBersih < 0 ? '&minus; ' : '' ?>Rp. <?= number_format(abs($labaBersih), 0, ',', '.') ?>
                </td>
            </tr>
        </table>
        <div class="recap-verdict">
            <?php if ($labaBersih >= 0): ?>
                &#10003;&nbsp; Bisnis dalam kondisi SURPLUS &mdash; margin <?= $marginPct ?>%, laba bersih <?= $labaPct ?>% dari omset
            <?php else: ?>
                &#9888;&nbsp; Bisnis dalam kondisi DEFISIT &mdash; biaya operasional melebihi margin sebesar Rp. <?= number_format(abs($labaBersih), 0, ',', '.') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ── Signature Area ── -->
<div class="section">
    <table class="sign-table">
        <tr>
            <td class="sign-cell" style="width:50%;">
                <div class="sign-label">Dibuat oleh,</div>
                <div class="sign-line"></div>
                <div class="sign-label" style="margin-top:5px;">Admin Jastip Arunga</div>
            </td>
            <td class="sign-cell" style="width:50%;">
                <div class="sign-label">Diketahui oleh,</div>
                <div class="sign-line"></div>
                <div class="sign-label" style="margin-top:5px;">Pemilik Usaha</div>
            </td>
        </tr>
    </table>
</div>

</div><!-- /body -->

<!-- ════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════ -->
<div class="page-footer">
    <table class="footer-table">
        <tr>
            <td>
                <strong>Jastip Arunga</strong> &mdash; Laporan Keuangan <?= htmlspecialchars($currentMonthLabel) ?>
            </td>
            <td class="footer-right txt-muted">
                Dokumen rahasia &middot; Dicetak <?= date('d/m/Y H:i') ?> &middot; &copy; <?= date('Y') ?> Jastip Arunga
            </td>
        </tr>
    </table>
</div>

</div><!-- /page -->
</body>
</html>
