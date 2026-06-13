<div class="px-4 md:px-margin-desktop py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="font-display-lg text-4xl font-bold text-primary mb-2">Kalkulasi Harga Jual</h1>
            <p class="text-on-surface-variant text-lg">HPP Dasar per item — harga beli + biaya langsung, sebelum biaya trip.</p>
        </div>
        <a href="?page=hpp&action=create" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-md flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Buat Kalkulasi Baru
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
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-outline-variant/50 text-primary">
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider">Nama Produk</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right">Total Biaya Item</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right">HPP Dasar / item</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-center">Margin</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-right">Harga Jual Dasar</th>
                        <th class="py-4 px-4 font-bold text-sm uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    <?php foreach ($hppList as $hpp): ?>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-4 px-4 font-bold text-on-surface"><?= htmlspecialchars($hpp->getName()) ?></td>
                        <td class="py-4 px-4 text-right text-on-surface-variant text-sm">
                            Rp. <?= number_format((float)$hpp->getTotalBiayaHpp(), 0, ',', '.') ?>
                        </td>
                        <td class="py-4 px-4 text-right font-medium text-on-surface">
                            Rp. <?= number_format((float)$hpp->getHppPerPcs(), 0, ',', '.') ?>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="bg-secondary-container/20 text-secondary-container px-3 py-1 rounded-full text-sm font-bold">
                                +Rp. <?= number_format((float)$hpp->getMarginKeuntungan(), 0, ',', '.') ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right font-black text-primary text-lg">
                            Rp. <?= number_format((float)$hpp->getHargaJualProduk(), 0, ',', '.') ?>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex gap-2 justify-center">
                                <a href="?page=products&action=create&hpp_id=<?= $hpp->getId() ?>"
                                   class="bg-primary/10 text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1"
                                   title="Buat Produk dari HPP ini">
                                    <span class="material-symbols-outlined text-[18px]">add_box</span> Buat Produk
                                </a>
                                <a href="?page=hpp&action=edit&id=<?= $hpp->getId() ?>"
                                   class="bg-surface-container border border-outline-variant/50 text-on-surface hover:bg-outline-variant/20 px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1"
                                   title="Edit HPP">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form method="POST" action="?page=hpp&action=delete&id=<?= $hpp->getId() ?>"
                                      onsubmit="return confirm('Hapus HPP \'<?= htmlspecialchars(addslashes($hpp->getName())) ?>\'?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors font-bold text-sm flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
