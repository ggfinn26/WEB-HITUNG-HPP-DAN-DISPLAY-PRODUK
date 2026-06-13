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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">

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
            <div class="overflow-x-auto relative z-10 bg-surface/50 md:rounded-2xl md:border border-outline-variant/30 md:border-outline-variant -mx-6 sm:-mx-8 md:mx-0">
                <table class="w-full text-sm border-collapse block md:table">
                    <thead class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider hidden md:table-header-group border-b border-outline-variant/50">
                        <tr>
                            <th class="px-4 py-3 w-10"></th>
                            <th class="px-4 py-3 text-left">No. Order</th>
                            <th class="px-4 py-3 text-left">Pemesan</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Tanggal</th>
                            <th class="px-4 py-3 text-right">Margin</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="flex flex-col md:table-row-group gap-4 md:gap-0 md:divide-y md:divide-outline-variant/30 px-4 md:px-0 bg-transparent md:bg-transparent">
                        <?php foreach ($orders as $order): ?>
                        <tr class="block md:table-row bg-surface hover:bg-surface-container/50 transition-colors rounded-2xl md:rounded-none border border-outline-variant/30 md:border-none shadow-sm md:shadow-none overflow-hidden pt-2 md:pt-0">
                            <td class="block md:table-cell px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">Pilih</span>
                                    <input type="checkbox" name="order_ids[]" value="<?= $order->getId() ?>"
                                           class="order-checkbox w-5 h-5 md:w-4 md:h-4 rounded accent-primary cursor-pointer">
                                </div>
                            </td>
                            <td class="block md:table-cell px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">No. Order</span>
                                    <div class="font-mono text-sm md:text-xs text-primary font-bold"><?= htmlspecialchars($order->getOrderNumber()) ?></div>
                                </div>
                            </td>
                            <td class="block md:table-cell px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">Pemesan</span>
                                    <div class="text-right md:text-left">
                                        <p class="font-bold text-on-surface"><?= htmlspecialchars($order->getNamaPemesan()) ?></p>
                                        <p class="text-xs text-on-surface-variant">@<?= htmlspecialchars($order->getInstagramUserNamePemesan()) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:hidden px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">Tanggal</span>
                                    <div class="text-on-surface-variant text-xs"><?= $order->getCreatedAt()->format('d M Y H:i') ?></div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-4 py-3 text-on-surface-variant text-xs"><?= $order->getCreatedAt()->format('d M Y H:i') ?></td>
                            <?php
                                $pItems  = json_decode($order->getListItemOrder(), true) ?? [];
                                $pMargin = array_reduce($pItems, function($carry, $item) {
                                    return $carry + (((float)($item['price'] ?? 0) - (float)($item['modal'] ?? 0)) * (int)($item['qty'] ?? 1));
                                }, 0.0);
                            ?>
                            <td class="block md:table-cell px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">Margin</span>
                                    <div class="text-right font-black text-on-surface">Rp. <?= number_format($pMargin, 0, ',', '.') ?></div>
                                </div>
                            </td>
                            <td class="block md:table-cell px-4 py-3 border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted">Status</span>
                                    <div class="text-right md:text-center">
                                        <span class="text-xs px-2 py-1 rounded-full font-bold
                                            <?= $order->getOrderStatus() === 'selesai' ? 'bg-green-100 text-green-700' : ($order->getOrderStatus() === 'dibatalkan' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') ?>">
                                            <?= htmlspecialchars($order->getOrderStatus()) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell px-4 py-3 bg-surface-container-low/50 md:bg-transparent">
                                <div class="flex justify-center md:justify-center gap-2">
                                    <a href="?page=orders&action=editDetail&id=<?= urlencode($order->getOrderNumber()) ?>"
                                       class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-primary/10 text-primary transition-colors flex items-center justify-center gap-1 font-bold text-xs w-full md:w-auto" title="Edit Detail">
                                        <span class="material-symbols-outlined text-[16px]">edit</span> Edit
                                    </a>
                                    <form method="POST" action="?page=laporan&action=deleteOrder&id=<?= $order->getId() ?>"
                                          onsubmit="return confirm('Hapus order <?= htmlspecialchars($order->getOrderNumber()) ?>?')" class="w-full md:w-auto flex">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helper\CsrfHelper::getToken()) ?>">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-red-100 text-red-500 transition-colors flex items-center justify-center gap-1 font-bold text-xs w-full" title="Hapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                                        </button>
                                    </form>
                                </div>
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
    if (!bulkBtn) return;
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

if (selectAll) {
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkState();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkState));
}
</script>
