document.addEventListener('DOMContentLoaded', () => {
    const btnAddGroup = document.getElementById('btn-add-variant-group');
    const groupsContainer = document.getElementById('variant-groups-container');
    const tableContainer = document.getElementById('variant-table-container');
    const tableHead = document.getElementById('variant-table-head');
    const tableBody = document.getElementById('variant-table-body');
    const imagesContainer = document.getElementById('variant-images-container');
    const imagesList = document.getElementById('variant-images-list');

    let groups = window.INITIAL_VARIANT_GROUPS || [];
    let variantImages = window.INITIAL_VARIANT_IMAGES || [];
    let initialVariants = window.INITIAL_VARIANTS || [];
    let basePrice = document.getElementById('price_display') ? document.getElementById('price_display').value.replace(/[^0-9]/g, '') : '';
    
    // Listen to base price change
    document.addEventListener('basePriceChanged', (e) => {
        basePrice = e.detail;
        // Update all empty price inputs in the table
        const priceInputs = document.querySelectorAll('.variant-price-input');
        priceInputs.forEach(input => {
            if (!input.value && basePrice) {
                input.value = basePrice;
            }
        });
    });

    btnAddGroup.addEventListener('click', () => {
        if (groups.length >= 2) {
            showAlert('Maksimal 2 tingkat variasi (Misal: Warna dan Ukuran)', 'warning');
            return;
        }
        groups.push({ name: '', options: [''] });
        renderGroups();
        renderTable();
    });

    function renderGroups() {
        groupsContainer.innerHTML = '';
        groups.forEach((group, groupIndex) => {
            const groupDiv = document.createElement('div');
            groupDiv.className = 'p-4 bg-surface rounded-xl border border-outline-variant/50 relative';
            
            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.innerHTML = '<span class="material-symbols-outlined text-sm">close</span>';
            btnRemove.className = 'absolute top-3 right-3 text-on-surface-variant hover:text-red-500 transition-colors bg-surface-container p-1 rounded-full';
            btnRemove.onclick = () => {
                groups.splice(groupIndex, 1);
                renderGroups();
                renderTable();
            };
            
            const nameLabel = document.createElement('label');
            nameLabel.className = 'block font-bold text-xs uppercase tracking-wider mb-2 text-on-surface-variant';
            nameLabel.textContent = `Nama Grup Variasi ${groupIndex + 1}`;
            
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = `variant_groups[]`;
            nameInput.placeholder = 'Misal: Warna, Ukuran...';
            nameInput.className = 'w-full px-4 py-2 rounded-lg border-2 border-outline-variant/30 bg-surface-container focus:bg-surface outline-none focus:border-primary mb-4';
            nameInput.value = group.name;
            nameInput.oninput = (e) => {
                group.name = e.target.value;
                renderTable();
            };

            const optsLabel = document.createElement('label');
            optsLabel.className = 'block font-bold text-xs uppercase tracking-wider mb-2 text-on-surface-variant';
            optsLabel.textContent = 'Pilihan Variasi (Pisahkan dengan koma atau Enter)';
            
            const optsInput = document.createElement('input');
            optsInput.type = 'text';
            optsInput.name = `variant_options[${group.name || 'group_'+groupIndex}]`;
            optsInput.placeholder = 'Misal: Merah, Biru, Hijau...';
            optsInput.className = 'w-full px-4 py-2 rounded-lg border-2 border-outline-variant/30 bg-surface-container focus:bg-surface outline-none focus:border-primary';
            optsInput.value = group.options.filter(o => o.trim() !== '').join(', ');
            
            // Allow comma or Enter to add
            optsInput.onkeyup = (e) => {
                const vals = e.target.value.split(',').map(v => v.trim()).filter(v => v !== '');
                group.options = vals.length > 0 ? vals : [''];
                // Update name of the input dynamically in case group name changes
                e.target.name = `variant_options[${group.name || 'group_'+groupIndex}]`;
                renderTable();
            };

            groupDiv.appendChild(btnRemove);
            groupDiv.appendChild(nameLabel);
            groupDiv.appendChild(nameInput);
            groupDiv.appendChild(optsLabel);
            groupDiv.appendChild(optsInput);
            
            groupsContainer.appendChild(groupDiv);
        });
        
        // Show images container if there's at least one option in the first group
        if (groups.length > 0 && groups[0].options.some(o => o.trim() !== '')) {
            imagesContainer.classList.remove('hidden');
            renderImageUploader();
        } else {
            imagesContainer.classList.add('hidden');
        }
    }
    
    function renderImageUploader() {
        imagesList.innerHTML = '';
        if (groups.length === 0) return;
        
        const firstGroupOpts = groups[0].options.filter(o => o.trim() !== '');
        
        firstGroupOpts.forEach((optName, idx) => {
            const wrap = document.createElement('div');
            wrap.className = 'flex-shrink-0 w-24 h-32 border border-outline-variant/50 rounded-xl bg-surface-container flex flex-col overflow-hidden relative group';
            
            const header = document.createElement('div');
            header.className = 'bg-surface-container-high text-xs text-center py-1 font-bold truncate px-1';
            header.textContent = optName;
            
            const imgBox = document.createElement('label');
            imgBox.className = 'flex-1 flex items-center justify-center cursor-pointer hover:bg-surface-container-high transition-colors relative';
            
            const icon = document.createElement('span');
            icon.className = 'material-symbols-outlined text-outline-variant';
            icon.textContent = 'add_photo_alternate';
            
            const imgPreview = document.createElement('img');
            imgPreview.className = 'absolute inset-0 w-full h-full object-cover hidden';
            
            // Check if there is an existing image
            if (variantImages[idx]) {
                imgPreview.src = variantImages[idx].url;
                imgPreview.classList.remove('hidden');
                icon.classList.add('hidden');
                
                // Add hidden input to preserve existing image
                const existingInput = document.createElement('input');
                existingInput.type = 'hidden';
                existingInput.name = `existing_variant_images[${idx}]`;
                existingInput.value = variantImages[idx].url;
                wrap.appendChild(existingInput);
            }
            
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = `variant_images[${idx}]`; // This will send array but sparse. We will fix it in controller by using normal array or processing files directly.
            fileInput.accept = 'image/*';
            fileInput.className = 'hidden';
            
            fileInput.onchange = (e) => {
                if(e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        imgPreview.src = ev.target.result;
                        imgPreview.classList.remove('hidden');
                        icon.classList.add('hidden');
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            };
            
            imgBox.appendChild(icon);
            imgBox.appendChild(imgPreview);
            imgBox.appendChild(fileInput);
            
            wrap.appendChild(header);
            wrap.appendChild(imgBox);
            imagesList.appendChild(wrap);
        });
    }

    // Helper to cartesian product options
    function getCombinations(arrays) {
        if (arrays.length === 0) return [];
        if (arrays.length === 1) return arrays[0].map(val => [val]);
        const result = [];
        const rest = getCombinations(arrays.slice(1));
        for (let i = 0; i < arrays[0].length; i++) {
            for (let j = 0; j < rest.length; j++) {
                result.push([arrays[0][i], ...rest[j]]);
            }
        }
        return result;
    }

    function renderTable() {
        const validGroups = groups.filter(g => g.name.trim() !== '');
        
        if (validGroups.length === 0) {
            tableContainer.classList.add('hidden');
            return;
        }

        const optionArrays = validGroups.map(g => g.options.filter(o => o.trim() !== ''));
        // Check if any group has 0 valid options
        if (optionArrays.some(arr => arr.length === 0)) {
            tableContainer.classList.add('hidden');
            return;
        }

        tableContainer.classList.remove('hidden');
        
        // Render Head
        tableHead.innerHTML = '';
        validGroups.forEach(g => {
            const th = document.createElement('th');
            th.className = 'px-4 py-3 border-r border-outline-variant/30';
            th.textContent = g.name;
            tableHead.appendChild(th);
        });
        
        ['Harga', 'SKU'].forEach(label => {
            const th = document.createElement('th');
            th.className = 'px-4 py-3 border-r border-outline-variant/30';
            th.textContent = label;
            tableHead.appendChild(th);
        });

        const combinations = getCombinations(optionArrays);
        
        // Save current inputs if they exist to not lose data
        const currentData = {};

        initialVariants.forEach(v => {
            currentData[v.name] = { price: v.price, sku: v.sku };
        });

        document.querySelectorAll('.variant-row').forEach(row => {
            const key = row.dataset.key;
            currentData[key] = {
                price: row.querySelector('.variant-price').value,
                sku: row.querySelector('.variant-sku').value,
            };
        });

        tableBody.innerHTML = '';
        combinations.forEach((combo, index) => {
            const tr = document.createElement('tr');
            tr.className = 'variant-row hover:bg-surface-container-low transition-colors';
            
            const comboKey = combo.join(' - ');
            tr.dataset.key = comboKey;
            
            // Name for submission
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `variants[${index}][name]`;
            nameInput.value = comboKey;
            tr.appendChild(nameInput);
            
            // Options array for submission
            const optsInput = document.createElement('input');
            optsInput.type = 'hidden';
            optsInput.name = `variants[${index}][options]`;
            optsInput.value = combo.join(',');
            tr.appendChild(optsInput);
            
            // Link to the primary group's image index
            const firstGroupOptIndex = validGroups[0].options.indexOf(combo[0]);
            const imageIndexInput = document.createElement('input');
            imageIndexInput.type = 'hidden';
            imageIndexInput.name = `variants[${index}][image_index]`;
            imageIndexInput.value = firstGroupOptIndex;
            tr.appendChild(imageIndexInput);

            combo.forEach((opt, colIdx) => {
                const td = document.createElement('td');
                td.className = 'px-4 py-3 border-r border-outline-variant/30';
                td.textContent = opt;
                tr.appendChild(td);
            });

            // Harga
            const tdPrice = document.createElement('td');
            tdPrice.className = 'px-4 py-2 border-r border-outline-variant/30';
            const inPrice = document.createElement('input');
            inPrice.type = 'number';
            inPrice.name = `variants[${index}][price]`;
            inPrice.className = 'variant-price variant-price-input w-24 px-2 py-1 border rounded bg-surface focus:border-primary outline-none';
            inPrice.placeholder = 'Rp';
            inPrice.value = currentData[comboKey]?.price || basePrice || '';
            tdPrice.appendChild(inPrice);
            tr.appendChild(tdPrice);

            // SKU
            const tdSku = document.createElement('td');
            tdSku.className = 'px-4 py-2';
            const inSku = document.createElement('input');
            inSku.type = 'text';
            inSku.name = `variants[${index}][sku]`;
            inSku.className = 'variant-sku w-full px-2 py-1 border rounded bg-surface focus:border-primary outline-none';
            inSku.placeholder = 'Opsional';
            inSku.value = currentData[comboKey]?.sku || '';
            tdSku.appendChild(inSku);
            tr.appendChild(tdSku);

            tableBody.appendChild(tr);
        });
        
        // Clear initialVariants so it doesn't overwrite user edits on next render
        initialVariants = [];
    }

    // Initial render if data exists
    if (groups.length > 0) {
        renderGroups();
        renderTable();
    }
});
