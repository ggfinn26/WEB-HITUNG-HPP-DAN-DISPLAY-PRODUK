<div class="px-4 md:px-margin-desktop py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
        <div>
            <h1 class="font-display-lg text-2xl md:text-4xl font-bold text-primary mb-1 md:mb-2">Manajemen Produk</h1>
            <p class="text-on-surface-variant text-base md:text-lg">Kelola katalog produk, harga, dan lokasi sumber barang jastip.</p>
        </div>
        <a href="?page=products&action=create" class="bg-primary text-white px-5 md:px-6 py-2.5 md:py-3 w-full sm:w-auto justify-center rounded-xl font-bold hover:bg-primary-container transition-colors shadow-md flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Produk
        </a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success_message']) ?></span>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="glass-panel p-6 rounded-3xl shadow-sm border border-outline-variant/30">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse block md:table">
                <thead class="hidden md:table-header-group">
                    <tr class="border-b-2 border-outline-variant/50 text-primary">
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm whitespace-nowrap">Nama Produk</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm whitespace-nowrap">Harga</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm whitespace-nowrap">Koordinat (Peta)</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="flex flex-col md:table-row-group gap-4 md:gap-0 md:divide-y md:divide-outline-variant/30">
                    <?php if (empty($products)): ?>
                        <tr class="block md:table-row">
                            <td colspan="4" class="block md:table-cell py-10 text-center text-on-surface-variant">Belum ada produk. Silakan tambahkan produk baru.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($products as $product): ?>
                            <tr class="block md:table-row bg-surface hover:bg-surface-container-low transition-colors rounded-2xl md:rounded-none border border-outline-variant/30 md:border-none shadow-sm md:shadow-none overflow-hidden">
                                <td class="block md:table-cell py-3 md:py-4 px-4 md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center md:block gap-1 sm:gap-4">
                                        <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Nama Produk</span>
                                        <div class="font-bold text-on-surface text-left sm:text-right md:text-left break-words"><?= htmlspecialchars($product->getName()) ?></div>
                                    </div>
                                </td>
                                <td class="block md:table-cell py-3 md:py-4 px-4 font-medium text-secondary-container md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                    <div class="flex justify-between items-center md:block">
                                        <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Harga</span>
                                        <div class="text-right md:text-left">Rp. <?= number_format((float)$product->getPrice(), 0, ',', '.') ?></div>
                                    </div>
                                </td>
                                <td class="block md:table-cell py-3 md:py-4 px-4 text-on-surface-variant text-sm md:whitespace-nowrap border-b border-outline-variant/10 md:border-none">
                                    <div class="flex justify-between items-center md:block">
                                        <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Koordinat (Peta)</span>
                                        <div class="flex justify-end md:justify-start">
                                            <?php if ($product->getLatitude() && $product->getLongitude()): ?>
                                                <div class="flex items-center gap-1 text-green-600 font-medium">
                                                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                                                    <?= $product->getLatitude() ?>, <?= $product->getLongitude() ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-orange-500 italic">Belum ada lokasi</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="block md:table-cell py-3 md:py-4 px-4 bg-surface-container-low/50 md:bg-transparent md:whitespace-nowrap">
                                    <div class="flex gap-2 justify-center">
                                        <a href="?page=products&action=edit&id=<?= $product->getId() ?>" class="bg-secondary-container/10 text-secondary-container hover:bg-secondary-container hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                                        </a>
                                        <form method="POST" action="?page=products&action=delete&id=<?= $product->getId() ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helper\CsrfHelper::getToken()) ?>">
                                            <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="flex flex-col md:flex-row items-center justify-between mt-6 relative z-10 gap-4">
            <p class="text-sm text-on-surface-variant font-medium">
                Halaman <?= $currentPage ?> dari <?= $totalPages ?> (<?= $totalProducts ?> produk)
            </p>
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto justify-center">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=products&p=<?= $currentPage - 1 ?>" class="px-4 py-2 rounded-xl bg-surface border border-outline-variant text-sm font-semibold hover:border-primary hover:text-primary transition-colors whitespace-nowrap">
                        &larr; Prev
                    </a>
                <?php endif; ?>
                <?php
                    $start = max(1, $currentPage - 2);
                    $end   = min($totalPages, $currentPage + 2);
                    for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="?page=products&p=<?= $i ?>"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors whitespace-nowrap <?= $i === $currentPage ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface border border-outline-variant hover:border-primary hover:text-primary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=products&p=<?= $currentPage + 1 ?>" class="px-4 py-2 rounded-xl bg-surface border border-outline-variant text-sm font-semibold hover:border-primary hover:text-primary transition-colors whitespace-nowrap">
                        Next &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
