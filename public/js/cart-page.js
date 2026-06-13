// Cart page — render items, qty controls, checkout injection

const fmt     = n => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
const escHtml = s => { const d = document.createElement('div'); d.textContent = s ?? ''; return d.innerHTML; };

function renderCart() {
    const cart      = getCart();
    const emptyEl   = document.getElementById('cart-empty');
    const contentEl = document.getElementById('cart-content');
    const listEl    = document.getElementById('cart-items-list');
    if (!emptyEl || !contentEl || !listEl) return;

    if (!cart.length) {
        emptyEl.classList.remove('hidden');
        contentEl.classList.add('hidden');
        return;
    }

    emptyEl.classList.add('hidden');
    contentEl.classList.remove('hidden');

    listEl.innerHTML = cart.map(item => `
        <div class="flex items-center gap-4 bg-surface rounded-2xl border border-outline-variant/20 p-4 shadow-sm">
            <div class="w-16 h-16 rounded-xl bg-surface-container-high flex-shrink-0 overflow-hidden flex items-center justify-center">
                ${item.imageUrl
                    ? `<img src="${escHtml(item.imageUrl)}" class="w-full h-full object-contain p-1" loading="lazy">`
                    : `<span class="text-2xl">📦</span>`}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-on-surface text-sm line-clamp-2">${escHtml(item.name)}</p>
                <p class="text-secondary font-black text-sm mt-0.5">${fmt(item.price)}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="changeQty(${item.id}, -1)"
                        class="w-8 h-8 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors font-bold text-lg">−</button>
                <span class="w-6 text-center font-bold text-sm">${item.qty}</span>
                <button onclick="changeQty(${item.id}, 1)"
                        class="w-8 h-8 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container transition-colors font-bold text-lg">+</button>
                <button onclick="removeFromCart(${item.id})"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-red-400 hover:bg-red-50 transition-colors ml-1">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        </div>
    `).join('');

    const totalQty   = cart.reduce((s, i) => s + i.qty, 0);
    const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('summary-qty').textContent   = totalQty + ' item';
    document.getElementById('summary-total').textContent = fmt(totalPrice);
}

function changeQty(id, delta) {
    const cart = getCart();
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    saveCart(cart);
    renderCart();
}

function removeFromCart(id) {
    saveCart(getCart().filter(i => i.id !== id));
    renderCart();
}

function clearCart() {
    showConfirm('Kosongkan semua item dari keranjang?', () => {
        saveCart([]);
        renderCart();
    }, 'Kosongkan');
}

function injectCart() {
    const cart = getCart();
    if (!cart.length) { showAlert('Keranjang kosong! Tambahkan produk terlebih dahulu.', 'warning'); return false; }
    const items = cart.map(i => ({ name: i.name, price: i.price, qty: i.qty, modal: 0 }));
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('list_item_order_input').value = JSON.stringify(items);
    document.getElementById('sub_total_input').value       = total;
    return true;
}

renderCart();
