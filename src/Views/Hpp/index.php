<div class="px-4 md:px-margin-desktop py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
        <div>
            <h1 class="font-display-lg text-2xl md:text-4xl font-bold text-primary mb-1 md:mb-2">Kalkulasi Harga Jual</h1>
            <p class="text-on-surface-variant text-base md:text-lg">HPP Dasar per item — harga beli + biaya langsung, sebelum biaya trip.</p>
        </div>
        <a href="?page=hpp&action=create" class="bg-primary text-white px-5 md:px-6 py-2.5 md:py-3 w-full sm:w-auto justify-center rounded-xl font-bold hover:bg-primary-container transition-colors shadow-md flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Buat Kalkulasi
        </a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Panduan -->
    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 mb-8 flex gap-4">
        <span class="material-symbols-outlined text-primary text-3xl flex-shrink-0">info</span>
        <div class="text-sm text-on-surface-variant leading-relaxed">
            <p class="font-bold text-on-surface mb-1">Alur Kerja Kalkulasi Jastip</p>
            <ol class="list-decimal list-inside space-y-1">
                <li>Buat kalkulasi di sini — masukkan <strong>harga beli + biaya langsung per item</strong> (packing, ongkos kirim dari toko).</li>
                <li>Setelah tersimpan, pergi ke <a href="?page=products&action=create" class="text-primary font-bold hover:underline">Tambah Produk</a> dan pilih kalkulasi ini — harga jual dasar akan terisi otomatis.</li>
                <li>Gunakan <a href="?page=sesi&action=create" class="text-primary font-bold hover:underline">Sesi Trip</a> untuk alokasi biaya trip (tiket, porter) dan dapatkan sugesti harga jual akhir.</li>
            </ol>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-3xl shadow-sm border border-outline-variant/30">
        <?php if (empty($hppList)): ?>
            <div class="text-center py-16">
                <span class="text-6xl">🧮</span>
                <h3 class="text-xl font-bold text-on-surface mt-4 mb-2">Belum Ada Kalkulasi</h3>
                <p class="text-on-surface-variant mb-6">Buat kalkulasi harga jual terlebih dahulu sebelum menambahkan produk ke katalog.</p>
                <a href="?page=hpp&action=create" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span> Buat Kalkulasi Pertama
                </a>
            </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse block md:table">
                <thead class="hidden md:table-header-group">
                    <tr class="border-b-2 border-outline-variant/50 text-primary">
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider whitespace-nowrap">Nama Produk</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right whitespace-nowrap">Total Biaya Item</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right whitespace-nowrap">HPP Dasar / item</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-center whitespace-nowrap">Margin</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right whitespace-nowrap">Harga Jual Dasar</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="flex flex-col md:table-row-group gap-4 md:gap-0 md:divide-y md:divide-outline-variant/30 px-4 md:px-0">
                    <?php if (empty($hppList)): ?>
                    <tr class="block md:table-row">
                        <td colspan="6" class="block md:table-cell px-4 py-8 text-center text-on-surface-variant">Belum ada data Kalkulasi Jastip.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($hppList as $hpp): ?>
                    <tr class="block md:table-row bg-surface hover:bg-surface-container/50 transition-colors rounded-2xl md:rounded-none border border-outline-variant/30 md:border-none shadow-sm md:shadow-none overflow-hidden md:overflow-visible pt-2 md:pt-0">
                        <td class="block md:table-cell py-3 md:py-4 px-4 border-b border-outline-variant/10 md:border-none">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center md:block gap-1 sm:gap-4">
                                <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Nama Produk</span>
                                <div class="font-bold text-on-surface text-left sm:text-right md:text-left break-words"><?= htmlspecialchars($hpp->getName()) ?></div>
                            </div>
                        </td>
                        <td class="block md:table-cell py-3 md:py-4 px-4 border-b border-outline-variant/10 md:border-none">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Total Biaya Item</span>
                                <div class="text-on-surface-variant text-sm text-right md:text-right">Rp. <?= number_format((float)$hpp->getTotalBiayaHpp(), 0, ',', '.') ?></div>
                            </div>
                        </td>
                        <td class="block md:table-cell py-3 md:py-4 px-4 border-b border-outline-variant/10 md:border-none">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">HPP Dasar / item</span>
                                <div class="font-medium text-on-surface text-right md:text-right">Rp. <?= number_format((float)$hpp->getHppPerPcs(), 0, ',', '.') ?></div>
                            </div>
                        </td>
                        <td class="block md:table-cell py-3 md:py-4 px-4 border-b border-outline-variant/10 md:border-none">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Margin</span>
                                <div class="text-right md:text-center">
                                    <span class="bg-secondary-container/20 text-secondary-container px-3 py-1 rounded-full text-sm font-bold">
                                        +Rp. <?= number_format((float)$hpp->getMarginKeuntungan(), 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="block md:table-cell py-3 md:py-4 px-4 border-b border-outline-variant/10 md:border-none">
                            <div class="flex justify-between items-center md:block">
                                <span class="md:hidden font-bold text-xs uppercase text-on-surface-variant flex-shrink-0">Harga Jual Dasar</span>
                                <div class="font-black text-primary text-lg text-right md:text-right">Rp. <?= number_format((float)$hpp->getHargaJualProduk(), 0, ',', '.') ?></div>
                            </div>
                        </td>
                        <td class="block md:table-cell py-3 md:py-4 px-4 bg-surface-container-low/50 md:bg-transparent">
                            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                <a href="?page=products&action=create&hpp_id=<?= $hpp->getId() ?>"
                                   class="bg-primary/10 text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex justify-center items-center gap-1 w-full sm:w-auto"
                                   title="Buat Produk dari HPP ini">
                                    <span class="material-symbols-outlined text-[18px]">add_box</span> Buat Produk
                                </a>
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <a href="?page=hpp&action=edit&id=<?= $hpp->getId() ?>"
                                       class="bg-surface-container border border-outline-variant/50 text-on-surface hover:bg-outline-variant/20 px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex flex-1 justify-center items-center gap-1"
                                       title="Edit HPP">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="?page=hpp&action=delete&id=<?= $hpp->getId() ?>"
                                          data-confirm="Hapus HPP '<?= htmlspecialchars($hpp->getName()) ?>'?" class="flex-1 flex">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Helper\CsrfHelper::getToken()) ?>">
                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex flex-1 justify-center items-center gap-1 w-full">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
