<div class="px-4 md:px-margin-desktop py-12">
    <div class="mb-8 flex items-center gap-4">
        <a href="?page=products" class="bg-surface-container-low text-on-surface p-3 rounded-full hover:bg-outline-variant/30 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-4xl font-bold text-primary mb-1">Edit Produk</h1>
            <p class="text-on-surface-variant text-lg">Perbarui informasi katalog atau lokasi asal barang.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="glass-panel p-8 md:p-10 rounded-3xl shadow-lg border border-outline-variant/30 max-w-4xl">
        <form action="?page=products&action=update&id=<?= $product->getId() ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Produk -->
                <div class="space-y-2">
                    <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Nama Produk *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($product->getName()) ?>"
                           class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                
                <!-- Harga Produk -->
                <div class="space-y-2">
                    <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Harga (Rp) *</label>
                    <input type="number" name="price" required value="<?= (float)$product->getPrice() ?>" min="0"
                           class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
            </div>

            <!-- Lokasi API Tergantung -->
            <div class="p-6 bg-primary-container/10 rounded-2xl border border-primary/20 space-y-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-primary">pin_drop</span>
                    <div>
                        <h3 class="font-bold text-primary text-lg">Perbarui Lokasi Asal Barang</h3>
                        <?php if ($product->getLatitude() && $product->getLongitude()): ?>
                            <p class="text-sm text-green-600 font-medium mt-1">Koordinat saat ini: <?= $product->getLatitude() ?>, <?= $product->getLongitude() ?></p>
                        <?php else: ?>
                            <p class="text-sm text-orange-500 font-medium mt-1">Belum ada lokasi untuk produk ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dropdown Provinsi -->
                    <div class="space-y-2">
                        <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Provinsi</label>
                        <select id="provinsi" class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Jika Ingin Mengubah...</option>
                        </select>
                        <input type="hidden" name="province" id="province_name">
                    </div>
                    
                    <!-- Dropdown Kota -->
                    <div class="space-y-2">
                        <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Kota / Kabupaten</label>
                        <select id="kota" disabled class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container-low focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">Pilih Provinsi Terlebih Dahulu...</option>
                        </select>
                        <input type="hidden" name="city" id="city_name">
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant italic">* Kosongkan pilihan jika tidak ingin mengubah koordinat lokasi yang sudah ada.</p>
            </div>

            <div class="space-y-2">
                <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Deskripsi Singkat</label>
                <textarea name="description" rows="3"
                          class="w-full px-5 py-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container focus:bg-surface focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"><?= htmlspecialchars($product->getDescription() ?? '') ?></textarea>
            </div>

            <!-- Upload Gambar Produk -->
            <div class="space-y-2">
                <label class="block font-bold text-on-surface text-sm uppercase tracking-wider">Gambar Produk <span class="font-normal normal-case text-on-surface-variant">(opsional — kosongkan jika tidak ingin mengganti)</span></label>
                <div id="drop-zone"
                     class="relative border-2 border-dashed border-outline-variant/60 rounded-2xl p-8 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all group">
                    <?php if ($product->getImageUrl()): ?>
                    <div id="drop-preview">
                        <img id="preview-img" src="<?= htmlspecialchars($product->getImageUrl()) ?>" alt="Gambar Produk"
                             class="max-h-56 mx-auto object-contain rounded-xl mb-3 shadow-md">
                        <p id="preview-name" class="text-sm font-bold text-on-surface">Gambar saat ini</p>
                        <p class="text-xs text-on-surface-variant mt-1">Klik atau drop gambar baru untuk mengganti</p>
                    </div>
                    <div id="drop-default" class="hidden">
                    <?php else: ?>
                    <div id="drop-preview" class="hidden">
                        <img id="preview-img" src="" alt="Preview" class="max-h-56 mx-auto object-contain rounded-xl mb-3 shadow-md">
                        <p id="preview-name" class="text-sm font-bold text-on-surface truncate"></p>
                        <p class="text-xs text-on-surface-variant mt-1">Klik atau drop gambar lain untuk mengganti</p>
                    </div>
                    <div id="drop-default">
                    <?php endif; ?>
                        <span class="material-symbols-outlined text-5xl text-outline-variant group-hover:text-primary transition-colors mb-3 block">cloud_upload</span>
                        <p class="font-bold text-on-surface mb-1">Drag & drop gambar di sini</p>
                        <p class="text-sm text-on-surface-variant">atau <span class="text-primary font-bold underline">klik untuk pilih file</span></p>
                        <p class="text-xs text-on-surface-variant mt-3">JPG, PNG, WebP · Maks. 5MB</p>
                    </div>
                    <input type="file" name="image_file" id="image_file_input" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex justify-end">
                <button type="submit" class="bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-container transition-colors shadow-lg active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // URL Base API Wilayah Indonesia
    const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const provinceNameInput = document.getElementById('province_name');
    const cityNameInput = document.getElementById('city_name');

    // 1. Fetch Provinces
    fetch(`${apiBase}/provinces.json`)
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(prov => {
                const option = document.createElement('option');
                option.value = prov.id;
                option.textContent = prov.name;
                option.dataset.name = prov.name; // Simpan nama aslinya
                provinsiSelect.appendChild(option);
            });
        })
        .catch(err => console.error('Gagal mengambil data provinsi:', err));

    // 2. Event Listener Provinsi ganti
    provinsiSelect.addEventListener('change', function() {
        const provId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        
        // Reset Kota
        kotaSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten...</option>';
        cityNameInput.value = '';
        
        if (provId) {
            provinceNameInput.value = selectedOption.dataset.name;
            kotaSelect.disabled = false;
            kotaSelect.classList.remove('bg-surface-container-low');
            kotaSelect.classList.add('bg-surface-container');
            
            // Fetch Kotas based on Provinsi ID
            fetch(`${apiBase}/regencies/${provId}.json`)
                .then(response => response.json())
                .then(regencies => {
                    regencies.forEach(reg => {
                        const option = document.createElement('option');
                        option.value = reg.id;
                        option.textContent = reg.name;
                        option.dataset.name = reg.name;
                        kotaSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Gagal mengambil data kota:', err));
        } else {
            provinceNameInput.value = '';
            kotaSelect.disabled = true;
            kotaSelect.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu...</option>';
        }
    });

    // 3. Event Listener Kota ganti
    kotaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            cityNameInput.value = selectedOption.dataset.name;
        } else {
            cityNameInput.value = '';
        }
    });

    // Drag & Drop Image Upload
    (function() {
        const zone    = document.getElementById('drop-zone');
        const input   = document.getElementById('image_file_input');
        const preview = document.getElementById('drop-preview');
        const defDiv  = document.getElementById('drop-default');
        const img     = document.getElementById('preview-img');
        const name    = document.getElementById('preview-name');

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) {
                alert('File harus berupa gambar.'); return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 5MB.'); return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                name.textContent = file.name;
                preview.classList.remove('hidden');
                defDiv.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => { if (input.files[0]) showPreview(input.files[0]); });

        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('border-primary', 'bg-primary/5', 'scale-[1.01]');
        });
        zone.addEventListener('dragleave', e => {
            if (!zone.contains(e.relatedTarget))
                zone.classList.remove('border-primary', 'bg-primary/5', 'scale-[1.01]');
        });
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('border-primary', 'bg-primary/5', 'scale-[1.01]');
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showPreview(file);
            }
        });
    }());
</script
