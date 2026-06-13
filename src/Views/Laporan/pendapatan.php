<div class="px-4 md:px-margin-desktop py-10 space-y-6">

    <div class="flex items-center gap-4">
        <a href="?page=laporan" class="bg-surface-container-low text-on-surface p-3 rounded-full hover:bg-outline-variant/30 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-3xl font-bold text-primary">Manajemen Pendapatan</h1>
            <p class="text-on-surface-variant mt-1">Kelola semua order — hapus satuan atau bulk.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl"><?= htmlspecialchars($_SESSION['success_message']) ?><?php unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl"><?= htmlspecialchars($_SESSION['error_message']) ?><?php unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="glass-panel p-12 rounded-2xl text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-3 block">inbox</span>
            Belum ada data order.
        </div>
    <?php else: ?>

    <form id="bulk-form" method="POST" action="?page=laporan&action=bulkDeleteOrders">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

        <div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
            <!-- Bulk Actions Bar -->
            <div class="p-4 border-b border-outline-variant/30 flex flex-wrap items-center justify-between gap-3 bg-surface-container/30">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="select-all" class="w-4 h-4 rounded accent-primary cursor-pointer">
                    <label for="select-all" class="text-sm font-bold text-on-surface cursor-pointer">Pilih Semua</label>
                    <span id="selected-count" class="text-xs text-on-surface-variant hidden"></span>
                </div>
                <button type="submit" id="bulk-delete-btn" disabled
                        onclick="return confirm('Hapus semua order yang dipilih? Tindakan ini tidak bisa dibatalkan.')"
                        class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold disabled:opacity-40 disabled:cursor-not-allowed hover:bg-red-600 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                    Hapus Terpilih
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 w-10"></th>
                            <th class="px-4 py-3 text-left">No. Order</th>
                            <th class="px-4 py-3 text-left">Pemesan</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-right">Margin</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                            <th class="px-4 py-3 text-center">Edit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-surface-container/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="order_ids[]" value="<?= $order->getId() ?>"
                                       class="order-checkbox w-4 h-4 rounded accent-primary cursor-pointer">
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-primary font-bold"><?= htmlspecialchars($order->getOrderNumber()) ?></td>
                            <td class="px-4 py-3">
                                <p class="font-bold text-on-surface"><?= htmlspecialchars($order->getNamaPemesan()) ?></p>
                                <p class="text-xs text-on-surface-variant">@<?= htmlspecialchars($order->getInstagramUserNamePemesan()) ?></p>
                            </td>
                            <td class="px-4 py-3 text-on-surface-variant text-xs hidden md:table-cell"><?= $order->getCreatedAt()->format('d M Y H:i') ?></td>
                            <?php
                                $pItems  = json_decode($order->getListItemOrder(), true) ?? [];
                                $pMargin = array_reduce($pItems, function($carry, $item) {
                                    return $carry + (((float)($item['price'] ?? 0) - (float)($item['modal'] ?? 0)) * (int)($item['qty'] ?? 1));
                                }, 0.0);
                            ?>
                            <td class="px-4 py-3 text-right font-black text-on-surface">Rp. <?= number_format($pMargin, 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs px-2 py-1 rounded-full font-bold
                                    <?= $order->getOrderStatus() === 'selesai' ? 'bg-green-100 text-green-700' : ($order->getOrderStatus() === 'dibatalkan' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') ?>">
                                    <?= htmlspecialchars($order->getOrderStatus()) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="?page=laporan&action=deleteOrder&id=<?= $order->getId() ?>"
                                      onsubmit="return confirm('Hapus order <?= htmlspecialchars($order->getOrderNumber()) ?>?')">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    <button type="submit" class="p-1.5 rounded-lg bg-surface-container hover:bg-red-100 text-red-500 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="?page=orders&action=editDetail&id=<?= urlencode($order->getOrderNumber()) ?>"
                                   class="p-1.5 rounded-lg bg-surface-container hover:bg-primary/10 text-primary transition-colors inline-flex" title="Edit Detail">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer total -->
            <div class="p-4 border-t border-outline-variant/30 bg-surface-container/30 flex justify-between items-center">
                <span class="text-sm text-on-surface-variant"><?= count($orders) ?> order total</span>
                <span class="font-black text-on-surface">
                    Total Margin: Rp. <?= number_format(array_sum(array_map(function($o) {
                        $its = json_decode($o->getListItemOrder(), true) ?? [];
                        return array_reduce($its, fn($c, $i) => $c + (((float)($i['price'] ?? 0) - (float)($i['modal'] ?? 0)) * (int)($i['qty'] ?? 1)), 0.0);
                    }, $orders)), 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
const selectAll    = document.getElementById('select-all');
const checkboxes   = document.querySelectorAll('.order-checkbox');
const bulkBtn      = document.getElementById('bulk-delete-btn');
const countLabel   = document.getElementById('selected-count');

function updateBulkState() {
    const checked = document.querySelectorAll('.order-checkbox:checked').length;
    bulkBtn.disabled = checked === 0;
    if (checked > 0) {
        countLabel.textContent = checked + ' dipilih';
        countLabel.classList.remove('hidden');
    } else {
        countLabel.classList.add('hidden');
    }
    selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
    selectAll.checked = checked === checkboxes.length && checkboxes.length > 0;
}

selectAll.addEventListener('change', () => {
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkState();
});

checkboxes.forEach(cb => cb.addEventListener('change', updateBulkState));
</script>
