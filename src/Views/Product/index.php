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
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-outline-variant/50 text-primary">
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm">Nama Produk</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm">Harga</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm">Koordinat (Peta)</th>
                        <th class="py-4 px-4 font-bold uppercase tracking-wider text-sm text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="4" class="py-10 text-center text-on-surface-variant">Belum ada produk. Silakan tambahkan produk baru.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($products as $product): ?>
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-on-surface"><?= htmlspecialchars($product->getName()) ?></div>
                                </td>
                                <td class="py-4 px-4 font-medium text-secondary-container">
                                    Rp. <?= number_format((float)$product->getPrice(), 0, ',', '.') ?>
                                </td>
                                <td class="py-4 px-4 text-on-surface-variant text-sm">
                                    <?php if ($product->getLatitude() && $product->getLongitude()): ?>
                                        <div class="flex items-center gap-1 text-green-600 font-medium">
                                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                                            <?= $product->getLatitude() ?>, <?= $product->getLongitude() ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-orange-500 italic">Belum ada lokasi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 flex gap-2 justify-center">
                                    <a href="?page=products&action=edit&id=<?= $product->getId() ?>" class="bg-secondary-container/10 text-secondary-container hover:bg-secondary-container hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                                    </a>
                                    <form method="POST" action="?page=products&action=delete&id=<?= $product->getId() ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
