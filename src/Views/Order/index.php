<div class="glass p-8 rounded-3xl shadow-xl border-t-4 border-t-primary relative overflow-hidden">
    <!-- Decorative element -->
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary opacity-5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-outline-variant pb-5 gap-4 relative z-10">
        <div>
            <h1 class="text-3xl font-black text-text tracking-tight">Daftar Order</h1>
            <p class="text-muted mt-1 text-sm">Kelola semua pesanan pelanggan di satu tempat.</p>
        </div>
        <a href="?page=orders&action=create" class="bg-primary text-white px-6 py-3 rounded-2xl shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5 transition-all font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Order
        </a>
    </div>

    <div class="overflow-x-auto relative z-10 bg-surface/50 md:rounded-2xl border-y md:border border-outline-variant/30 md:border-outline-variant -mx-4 md:mx-0">
        <table class="w-full text-left border-collapse block md:table">
            <thead class="hidden md:table-header-group">
                <tr class="text-muted border-b border-outline-variant bg-background/50">
                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider whitespace-nowrap">No. Seri</th>
                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider whitespace-nowrap">Pelanggan</th>
                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider whitespace-nowrap">Total</th>
                    <th class="py-4 px-6 font-semibold text-sm uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th class="py-4 px-6 text-right font-semibold text-sm uppercase tracking-wider whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="flex flex-col md:table-row-group gap-4 md:gap-0 md:divide-y md:divide-outline-variant px-4 md:px-0 bg-transparent md:bg-transparent">
                <?php if (empty($orders)): ?>
                    <tr class="block md:table-row w-full">
                        <td colspan="6" class="block md:table-cell py-12 text-center text-muted w-full">
                            <div class="flex flex-col items-center justify-center w-full">
                                <svg class="w-12 h-12 mb-3 text-muted/50 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="block w-full text-center">Belum ada pesanan masuk.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="block md:table-row bg-surface hover:bg-background/80 transition-colors group rounded-2xl md:rounded-none border border-outline-variant/30 md:border-none shadow-sm md:shadow-none overflow-hidden pt-2 md:pt-0">
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center md:block gap-1 sm:gap-4">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted flex-shrink-0">No. Seri</span>
                                    <div class="font-mono font-bold text-primary text-sm text-left sm:text-right md:text-left break-words">
                                        <?php echo htmlspecialchars($order->getOrderNumber()); ?>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block gap-1">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted flex-shrink-0">Pelanggan</span>
                                    <div class="font-medium text-text text-right md:text-left break-words">
                                        <?php echo htmlspecialchars($order->getNamaPemesan()); ?>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted flex-shrink-0">Tanggal</span>
                                    <div class="text-muted text-sm text-right md:text-left">
                                        <?php echo htmlspecialchars($order->getCreatedAt()->format('d M Y, H:i')); ?>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted flex-shrink-0">Total</span>
                                    <div class="font-bold text-text tracking-tight text-right md:text-left">
                                        Rp. <?php echo number_format((float)$order->getSubTotal(), 0, ',', '.'); ?>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                <div class="flex justify-between items-center md:block">
                                    <span class="md:hidden font-bold text-xs uppercase text-muted flex-shrink-0">Status</span>
                                    <div class="text-right md:text-left">
                                        <?php 
                                            $status = $order->getOrderStatus(); 
                                            $statusColor = $status === 'Pending' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-green-100 text-green-700 border-green-200';
                                        ?>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm <?php echo $statusColor; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="block md:table-cell py-3 md:py-4 px-4 md:px-6 md:whitespace-nowrap bg-background/50 md:bg-transparent">
                                <div class="flex justify-center md:justify-end">
                                    <a href="?page=orders&action=show&id=<?php echo urlencode($order->getOrderNumber()); ?>" class="inline-flex items-center justify-center gap-1 text-sm text-secondary hover:text-primary font-bold transition-colors py-2 md:py-1.5 px-4 md:px-3 bg-surface border border-outline-variant rounded-lg shadow-sm hover:shadow-md w-full md:w-auto">
                                        Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-between mt-6 relative z-10">
        <p class="text-sm text-muted">
            Halaman <?= $currentPage ?> dari <?= $totalPages ?> (<?= $totalOrders ?> order)
        </p>
        <div class="flex items-center gap-2">
            <?php if ($currentPage > 1): ?>
                <a href="?page=orders&p=<?= $currentPage - 1 ?>" class="px-4 py-2 rounded-xl bg-surface border border-outline-variant text-sm font-semibold hover:border-primary hover:text-primary transition-colors">
                    &larr; Prev
                </a>
            <?php endif; ?>
            <?php
                $start = max(1, $currentPage - 2);
                $end   = min($totalPages, $currentPage + 2);
                for ($i = $start; $i <= $end; $i++):
            ?>
                <a href="?page=orders&p=<?= $i ?>"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors <?= $i === $currentPage ? 'bg-primary text-white shadow-sm' : 'bg-surface border border-outline-variant hover:border-primary hover:text-primary' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=orders&p=<?= $currentPage + 1 ?>" class="px-4 py-2 rounded-xl bg-surface border border-outline-variant text-sm font-semibold hover:border-primary hover:text-primary transition-colors">
                    Next &rarr;
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
