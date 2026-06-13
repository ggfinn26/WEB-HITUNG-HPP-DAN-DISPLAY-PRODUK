// Global cart utilities — loaded on every page via layout.php

// ── Cart ─────────────────────────────────────────────────────────────────────

function getCart() {
    return JSON.parse(localStorage.getItem('mbutitip_cart') || '[]');
}

function saveCart(cart) {
    localStorage.setItem('mbutitip_cart', JSON.stringify(cart));
    updateCartBadge();
}

function updateCartBadge() {
    const total = getCart().reduce((s, i) => s + i.qty, 0);
    const icon = document.getElementById('cart-icon');
    if (!icon) return;
    if (total > 0) {
        // Ada barang — nyalakan warna (primary)
        icon.style.color = 'var(--md-sys-color-primary, #6750A4)';
        icon.setAttribute('data-fill', '1');
    } else {
        // Kosong — matikan warna (muted)
        icon.style.color = '';
        icon.removeAttribute('data-fill');
    }
}

function addToCart(btn) {
    const card  = btn.closest('[data-product-id]');
    const id    = parseInt(card.dataset.productId);
    const name  = card.dataset.productName;
    const price = parseFloat(card.dataset.productPrice);
    const img   = card.dataset.productImage || '';
    const cart  = getCart();
    const exist = cart.find(i => i.id === id);
    if (exist) { exist.qty++; } else { cart.push({ id, name, price, imageUrl: img, qty: 1 }); }
    saveCart(cart);
    const icon = btn.querySelector('.material-symbols-outlined') || btn;
    const prev = icon.textContent;
    icon.textContent = 'check';
    icon.classList.add('text-green-500');
    setTimeout(() => { icon.textContent = prev; icon.classList.remove('text-green-500'); }, 1200);
}

// ── Alert Modal ───────────────────────────────────────────────────────────────

const _alertIcons = { info: 'info', warning: 'warning', error: 'cancel', success: 'check_circle' };
const _alertColors = { info: 'text-primary', warning: 'text-amber-500', error: 'text-red-500', success: 'text-green-600' };

function showAlert(message, type = 'info') {
    const overlay = document.getElementById('app-alert-overlay');
    if (!overlay) { console.warn(message); return; }
    document.getElementById('app-alert-msg').textContent = message;
    const icon = document.getElementById('app-alert-icon');
    icon.textContent = _alertIcons[type] || _alertIcons.info;
    icon.className   = 'material-symbols-outlined text-[40px] mb-3 block ' + (_alertColors[type] || _alertColors.info);
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAppAlert() {
    const overlay = document.getElementById('app-alert-overlay');
    if (overlay) overlay.style.display = 'none';
    document.body.style.overflow = '';
}

// ── Confirm Modal ─────────────────────────────────────────────────────────────

let _confirmCallback = null;

function showConfirm(message, onConfirm, okLabel = 'Hapus') {
    const overlay = document.getElementById('app-confirm-overlay');
    if (!overlay) { if (confirm(message)) onConfirm(); return; }
    document.getElementById('app-confirm-msg').textContent    = message;
    document.getElementById('app-confirm-ok-btn').textContent = okLabel;
    _confirmCallback = onConfirm;
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAppConfirm(confirmed) {
    const overlay = document.getElementById('app-confirm-overlay');
    if (overlay) overlay.style.display = 'none';
    document.body.style.overflow = '';
    if (confirmed && _confirmCallback) _confirmCallback();
    _confirmCallback = null;
}

// ── Global data-confirm handler for forms ────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', function(e) {
        const msg = e.target.dataset.confirm;
        if (!msg) return;
        e.preventDefault();
        showConfirm(msg, () => {
            delete e.target.dataset.confirm;
            e.target.submit();
        });
    });
});

updateCartBadge();
