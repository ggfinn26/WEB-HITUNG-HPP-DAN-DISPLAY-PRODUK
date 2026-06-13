<style>
/* ── Catalog page styles — matches experiment-catalog.html ── */
#catalogGrid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    padding: 12px;
}
@media (min-width: 640px)  { #catalogGrid { grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 20px; } }
@media (min-width: 1024px) { #catalogGrid { grid-template-columns: repeat(4, 1fr); } }

.cat-card {
    background: var(--color-surface, #fff);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.06);
    transition: transform 0.15s, box-shadow 0.15s;
}
.cat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
.cat-card-img {
    width: 100%;
    aspect-ratio: 1/1;
    background: var(--color-surface-container-low, #eff4ff);
    overflow: hidden;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cat-card-img img { width: 100%; height: 100%; object-fit: cover; }
.cat-card-body { padding: 8px 8px 10px; display: flex; flex-direction: column; flex: 1; }
.cat-card-name {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-on-surface, #0b1c30);
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 4px;
    flex: 1;
    cursor: pointer;
}
@media (min-width: 640px) { .cat-card-name { font-size: 13px; } }
.cat-card-price { font-size: 13px; font-weight: 800; color: var(--color-primary, #001e40); margin-bottom: 8px; }
.cat-card-actions { display: flex; flex-direction: column; gap: 5px; }
.cat-btn-detail {
    width: 100%;
    background: var(--color-primary, #001e40);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 7px 0;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    transition: opacity 0.15s;
}
.cat-btn-detail:active { opacity: 0.8; transform: scale(0.97); }
.cat-btn-cart {
    width: 100%;
    background: var(--color-surface-container-low, #eff4ff);
    color: var(--color-primary, #001e40);
    border: 1px solid var(--color-outline-variant, #c3c6d1);
    border-radius: 8px;
    padding: 7px 0;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    transition: all 0.15s;
}
.cat-btn-cart:active { background: var(--color-primary, #001e40); color: #fff; transform: scale(0.97); }

/* Modal */
#catalogModal {
    position: fixed;
    inset: 0;
    z-index: 80;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s;
    background: rgba(0,0,0,0.5);
}
#catalogModal.open { opacity: 1; pointer-events: all; }
#catalogModalSheet {
    position: relative;
    background: var(--color-surface, #fff);
    width: 100%;
    max-width: 480px;
    border-radius: 20px 20px 0 0;
    display: flex;
    flex-direction: column;
    height: 100dvh;
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
}
#catalogModalSheet.open { transform: translateY(0); }
@media (min-width: 640px) {
    #catalogModal { align-items: center; backdrop-filter: blur(12px); background: rgba(0,0,0,0.3); }
    #catalogModalSheet {
        height: auto;
        max-height: 88vh;
        border-radius: 20px;
        max-width: 560px;
        transform: scale(0.95);
    }
    #catalogModalSheet.open { transform: scale(1); }
    #modalCarouselWrap { height: 300px !important; max-height: 300px !important; }
}

/* Carousel */
#modalCarouselWrap {
    position: relative;
    width: 100%;
    height: 60vw;
    max-height: 320px;
    overflow: hidden;
    background: var(--color-surface-container-low, #eff4ff);
    cursor: grab;
    flex-shrink: 0;
}
#modalCarouselTrack { display: flex; height: 100%; transition: transform 0.3s ease-out; will-change: transform; }
.carousel-slide { flex: 0 0 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.carousel-slide img { width: 100%; height: 100%; object-fit: contain; padding: 10px; }

.modal-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.85);
    border: none;
    border-radius: 99px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    z-index: 2;
    transition: background 0.15s;
}
.modal-nav-btn:hover { background: #fff; }
.modal-nav-btn.hidden { display: none; }
#modalPrev { left: 8px; }
#modalNext { right: 8px; }

#modalDots { display: flex; justify-content: center; gap: 4px; padding: 6px 0 2px; flex-shrink: 0; }
.modal-dot { width: 5px; height: 5px; border-radius: 99px; background: #d1d5db; transition: all 0.2s; cursor: pointer; flex-shrink: 0; }
.modal-dot.active { width: 14px; background: var(--color-primary, #001e40); }

/* Modal inner */
.modal-topbar { flex-shrink: 0; display: flex; align-items: center; justify-content: flex-end; padding: 12px 14px 6px; }
.modal-close-btn {
    background: var(--color-surface-container-low, #eff4ff);
    border: none;
    border-radius: 99px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    color: var(--color-on-surface, #0b1c30);
}
.modal-close-btn:hover { background: var(--color-outline-variant, #c3c6d1); }
.modal-scrollable { flex: 1; overflow-y: auto; padding: 10px 16px 0; }
.modal-prod-name { font-size: 15px; font-weight: 700; color: var(--color-on-surface, #0b1c30); margin-bottom: 2px; line-height: 1.4; }
.modal-prod-price { font-size: 20px; font-weight: 900; color: var(--color-primary, #001e40); margin-bottom: 10px; }
.variant-group { margin-bottom: 10px; }
.variant-group-label { font-size: 10px; font-weight: 700; color: var(--color-on-surface-variant, #43474f); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px; }
.variant-opts { display: flex; flex-wrap: wrap; gap: 5px; }
.variant-chip {
    padding: 4px 12px;
    border-radius: 99px;
    border: 1.5px solid var(--color-outline-variant, #c3c6d1);
    font-size: 12px;
    font-weight: 600;
    color: var(--color-on-surface, #0b1c30);
    background: var(--color-surface, #fff);
    cursor: pointer;
    transition: all 0.15s;
}
.variant-chip.active { border-color: var(--color-primary, #001e40); background: var(--color-primary, #001e40); color: #fff; }
.modal-desc-label { font-size: 10px; font-weight: 700; color: var(--color-on-surface-variant, #43474f); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; margin-top: 10px; }
.modal-desc-text { font-size: 13px; color: var(--color-on-surface-variant, #43474f); line-height: 1.6; }
.modal-desc-text p { margin-bottom: 6px; }
.modal-desc-text p:last-child { margin-bottom: 0; }
.modal-desc-text ul, .modal-desc-text ol { padding-left: 18px; margin-bottom: 6px; }
.modal-desc-text ul { list-style: disc; }
.modal-desc-text ol { list-style: decimal; }
.modal-desc-text li { margin-bottom: 2px; }
.modal-footer { flex-shrink: 0; padding: 12px 16px 20px; border-top: 1px solid var(--color-outline-variant, #c3c6d1); background: var(--color-surface, #fff); }
.modal-cart-btn {
    width: 100%;
    background: var(--color-primary, #001e40);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 14px 0;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: opacity 0.15s;
}
.modal-cart-btn:active { opacity: 0.85; transform: scale(0.98); }
.modal-cart-btn:disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; transform: none; }
</style>

<?php
$jsProducts = [];
foreach ($products as $p) {
    $pid = $p->getId();
    $vd  = $variantsData[$pid] ?? ['groups' => [], 'variants' => [], 'images' => []];
    $images = [];
    if ($p->getImageUrl()) $images[] = $p->getImageUrl();
    foreach ($vd['images'] as $img) {
        if ($img['url'] !== $p->getImageUrl()) $images[] = $img['url'];
    }
    if (empty($images)) $images[] = '';
    $jsProducts[$pid] = [
        'id'          => $pid,
        'name'        => $p->getName(),
        'price'       => (float)$p->getPrice(),
        'imageUrl'    => $p->getImageUrl() ?? '',
        'description' => $p->getDescription() ?? '',
        'images'      => $images,
        'groups'      => $vd['groups'],
        'variants'    => $vd['variants'],
        'varImages'   => $vd['images'],
    ];
}
?>

<!-- Hero -->
<section class="relative overflow-hidden pt-20 sm:pt-28 pb-10 sm:pb-16 px-4 md:px-margin-desktop bg-surface-container-low rounded-b-[3rem] shadow-sm mb-6">
    <div class="max-w-7xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-primary font-bold text-sm mb-6" style="background:rgba(0,30,64,0.08);">
            <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
            Jastip Arunga
        </div>
        <h1 class="font-display-lg text-4xl md:text-5xl font-bold text-primary mb-4">Katalog Produk Lengkap</h1>
        <p class="font-body-lg text-lg text-on-surface-variant max-w-2xl mx-auto">
            Jelajahi seluruh koleksi barang jastip favorit keluarga dari berbagai pelosok negeri.
        </p>
    </div>
</section>

<!-- Search bar sticky -->
<div style="position:sticky;top:72px;z-index:30;background:var(--color-surface,#fff);border-bottom:1px solid var(--color-outline-variant,#c3c6d1);padding:10px 16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);">
    <div style="max-width:1280px;margin:0 auto;position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--color-on-surface-variant,#43474f);font-size:20px;">search</span>
        <input type="text" id="catalogSearch" placeholder="Cari produk..."
               style="width:100%;padding:10px 16px 10px 44px;border-radius:99px;border:1.5px solid var(--color-outline-variant,#c3c6d1);background:var(--color-surface-container-low,#eff4ff);font-size:14px;outline:none;transition:border-color 0.2s,background 0.2s;color:var(--color-on-surface,#0b1c30);"
               onfocus="this.style.borderColor='var(--color-primary,#001e40)';this.style.background='var(--color-surface,#fff)'"
               onblur="this.style.borderColor='var(--color-outline-variant,#c3c6d1)';this.style.background='var(--color-surface-container-low,#eff4ff)'">
    </div>
</div>

<div style="max-width:1280px;margin:0 auto;padding:6px 16px 2px;font-size:12px;color:var(--color-on-surface-variant,#43474f);" id="catalogCount"></div>

<?php if (empty($products)): ?>
<div style="max-width:1280px;margin:0 auto;padding:80px 20px;text-align:center;">
    <div style="font-size:48px;margin-bottom:16px;">&#128717;</div>
    <h3 class="text-2xl font-bold text-primary mb-3">Belum Ada Produk</h3>
    <p class="text-on-surface-variant">Admin sedang berkeliling nusantara mencari barang impianmu.</p>
</div>
<?php else: ?>

<div style="max-width:1280px;margin:0 auto;padding-bottom:100px;">
    <div id="catalogGrid"></div>
    <div id="catalogEmpty" style="display:none;text-align:center;padding:60px 20px;color:var(--color-on-surface-variant,#43474f);">
        <span class="material-symbols-outlined" style="font-size:40px;display:block;margin-bottom:8px;color:var(--color-outline-variant,#c3c6d1);">search_off</span>
        Produk tidak ditemukan
    </div>
    <div id="catalogSentinel" style="height:1px;margin-top:16px;"></div>
    <div id="catalogLoader" style="display:none;text-align:center;padding:16px;font-size:13px;color:var(--color-on-surface-variant,#43474f);">Memuat...</div>
</div>

<?php endif; ?>

<!-- Modal -->
<div id="catalogModal" onclick="handleModalOverlayClick(event)">
    <div id="catalogModalSheet">

        <!-- Topbar -->
        <div class="modal-topbar">
            <button class="modal-close-btn" onclick="closeCatalogModal()">
                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
            </button>
        </div>

        <!-- Carousel -->
        <div id="modalCarouselWrap">
            <div id="modalCarouselTrack"></div>
            <button class="modal-nav-btn hidden" id="modalPrev" onclick="moveModalCarousel(-1)">&#8249;</button>
            <button class="modal-nav-btn hidden" id="modalNext" onclick="moveModalCarousel(1)">&#8250;</button>
        </div>
        <div id="modalDots"></div>

        <!-- Scrollable content -->
        <div class="modal-scrollable">
            <p class="modal-prod-name" id="modalName"></p>
            <p class="modal-prod-price" id="modalPrice"></p>
            <div id="modalVariants"></div>
            <div id="modalDescWrap" style="display:none;">
                <p class="modal-desc-label">Deskripsi</p>
                <div class="modal-desc-text" id="modalDesc"></div>
            </div>
        </div>

        <!-- Footer CTA -->
        <div class="modal-footer">
            <button class="modal-cart-btn" id="modalCartBtn" onclick="addCurrentToCart()">
                <span class="material-symbols-outlined" style="font-size:20px;">add_shopping_cart</span>
                Masuk Keranjang
            </button>
        </div>
    </div>
</div>

<script>
(function() {
// Move modal to <body> so it escapes <main>'s stacking context and covers the sticky header
var _m = document.getElementById('catalogModal');
if (_m && _m.parentNode !== document.body) document.body.appendChild(_m);

var PRODUCTS     = <?= json_encode(array_values($jsProducts), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
var variantsData = <?= json_encode($variantsData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
var fmt = function(n){ return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n)); };
var PER_PAGE = 12, filtered = PRODUCTS.slice(), rendered = 0, loading = false;

// ── Grid cards ────────────────────────────────────────────────────────────────
function cardHtml(p) {
    var imgHtml = p.imageUrl
        ? '<img src="'+p.imageUrl+'" alt="'+p.name.replace(/"/g,'&quot;')+'" loading="lazy" style="width:100%;height:100%;object-fit:cover;">'
        : '<span class="material-symbols-outlined" style="font-size:2.5rem;color:var(--color-outline-variant,#c3c6d1);">inventory_2</span>';
    return '<div class="cat-card">'
        + '<div class="cat-card-img" onclick="openCatalogModal('+p.id+')">'+imgHtml+'</div>'
        + '<div class="cat-card-body">'
        + '<p class="cat-card-name" onclick="openCatalogModal('+p.id+')">'+p.name+'</p>'
        + '<div style="margin-top:auto;padding-top:8px;">'
        + '<p class="cat-card-price">'+fmt(p.price)+'</p>'
        + '<div class="cat-card-actions">'
        + '<button class="cat-btn-detail" onclick="openCatalogModal('+p.id+')">'
        + '<span class="material-symbols-outlined" style="font-size:15px;">visibility</span> Detail</button>'
        + '<button class="cat-btn-cart" onclick="gridAddToCart('+p.id+',this)">'
        + '<span class="material-symbols-outlined" style="font-size:15px;">add_shopping_cart</span> Keranjang</button>'
        + '</div></div></div></div>';
}

function renderBatch() {
    if (loading) return;
    var batch = filtered.slice(rendered, rendered + PER_PAGE);
    if (!batch.length) { document.getElementById('catalogLoader').style.display='none'; return; }
    loading = true;
    setTimeout(function() {
        var grid = document.getElementById('catalogGrid');
        batch.forEach(function(p) { grid.insertAdjacentHTML('beforeend', cardHtml(p)); });
        rendered += batch.length; loading = false;
        document.getElementById('catalogLoader').style.display = rendered < filtered.length ? 'block' : 'none';
    }, 150);
}

function applySearch(q) {
    document.getElementById('catalogGrid').innerHTML = ''; rendered = 0;
    filtered = q ? PRODUCTS.filter(function(p){ return p.name.toLowerCase().indexOf(q.toLowerCase()) !== -1; }) : PRODUCTS.slice();
    var empty = document.getElementById('catalogEmpty'), count = document.getElementById('catalogCount');
    if (!filtered.length) { empty.style.display='block'; count.textContent=''; }
    else { empty.style.display='none'; count.textContent=filtered.length+' produk'; renderBatch(); }
}

var dt;
document.getElementById('catalogSearch').addEventListener('input', function() {
    clearTimeout(dt); var v=this.value.trim(); dt=setTimeout(function(){ applySearch(v); },250);
});

new IntersectionObserver(function(e) {
    if (e[0].isIntersecting && rendered < filtered.length) renderBatch();
}, { rootMargin:'300px' }).observe(document.getElementById('catalogSentinel'));

applySearch('');

// ── Grid add to cart ──────────────────────────────────────────────────────────
function gridAddToCart(id, btn) {
    var p=PRODUCTS.find(function(x){return x.id===id;}); if(!p) return;
    if (p.groups && p.groups.length > 0) { openCatalogModal(id); return; }
    var cart=getCart(), exist=cart.find(function(i){return i.id===id;});
    if (exist) exist.qty++; else cart.push({id:p.id,name:p.name,price:p.price,imageUrl:p.imageUrl,qty:1});
    saveCart(cart);
    var orig=btn.innerHTML;
    btn.innerHTML='<span class="material-symbols-outlined" style="font-size:15px;">check</span> Ditambahkan';
    btn.style.background='#16a34a'; btn.style.color='#fff'; btn.style.borderColor='#15803d';
    setTimeout(function(){
        btn.innerHTML=orig;
        btn.style.background=''; btn.style.color=''; btn.style.borderColor='';
    }, 1500);
}
window.gridAddToCart=gridAddToCart;

// ── Carousel ──────────────────────────────────────────────────────────────────
var ci=0, ct=0;
function buildModalCarousel(images) {
    var track=document.getElementById('modalCarouselTrack'), dots=document.getElementById('modalDots');
    track.innerHTML=''; dots.innerHTML=''; ci=0; ct=images.length;
    images.forEach(function(src,i) {
        var slide=document.createElement('div'); slide.className='carousel-slide';
        slide.innerHTML=src
            ? '<img src="'+src+'" loading="lazy">'
            : '<span class="material-symbols-outlined" style="font-size:3rem;color:#9ca3af;">inventory_2</span>';
        track.appendChild(slide);
        var dot=document.createElement('div'); dot.className='modal-dot'+(i===0?' active':'');
        dot.onclick=function(){goToSlide(i);}; dots.appendChild(dot);
    });
    updateCarousel();
}
function updateCarousel() {
    document.getElementById('modalCarouselTrack').style.transform='translateX(-'+(ci*100)+'%)';
    document.querySelectorAll('.modal-dot').forEach(function(d,i){
        d.style.width=i===ci?'14px':'5px';
        d.style.background=i===ci?'var(--color-primary,#001e40)':'#d1d5db';
    });
    document.getElementById('modalPrev').classList.toggle('hidden',ci===0);
    document.getElementById('modalNext').classList.toggle('hidden',ci===ct-1);
}
function moveModalCarousel(dir){ci=Math.max(0,Math.min(ct-1,ci+dir));updateCarousel();}
function goToSlide(i){ci=i;updateCarousel();}
window.moveModalCarousel=moveModalCarousel;

// Touch swipe
var txS=0,tyS=0,isH=null,cw=document.getElementById('modalCarouselWrap');
cw.addEventListener('touchstart',function(e){txS=e.touches[0].clientX;tyS=e.touches[0].clientY;isH=null;},{passive:true});
cw.addEventListener('touchmove',function(e){
    if(isH===null){var dx=Math.abs(e.touches[0].clientX-txS),dy=Math.abs(e.touches[0].clientY-tyS);if(dx>5||dy>5)isH=dx>dy;}
    if(isH)e.preventDefault();
},{passive:false});
cw.addEventListener('touchend',function(e){if(!isH)return;var d=txS-e.changedTouches[0].clientX;if(Math.abs(d)>40)moveModalCarousel(d>0?1:-1);});

// Mouse drag
var mx=0,drag=false,dragged=false;
cw.addEventListener('mousedown',function(e){mx=e.clientX;drag=true;dragged=false;e.preventDefault();});
document.addEventListener('mousemove',function(e){if(!drag)return;if(Math.abs(e.clientX-mx)>5)dragged=true;});
document.addEventListener('mouseup',function(e){if(!drag)return;drag=false;if(!dragged)return;var d=mx-e.clientX;if(Math.abs(d)>40)moveModalCarousel(d>0?1:-1);});

// Trackpad
var wa=0,wl=false;
cw.addEventListener('wheel',function(e){
    if(Math.abs(e.deltaX)<Math.abs(e.deltaY))return;e.preventDefault();if(wl)return;
    wa+=e.deltaX;if(Math.abs(wa)>60){moveModalCarousel(wa>0?1:-1);wa=0;wl=true;setTimeout(function(){wl=false;},500);}
},{passive:false});

// ── Description formatter ──────────────────────────────────────────────────────
function descToHtml(text) {
    if (!text) return '';
    // Escape raw HTML entities first
    text = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    var lines = text.split('\n');
    var out = [], inUl = false, inOl = false;
    lines.forEach(function(line) {
        var t = line.trim();
        var isBullet = /^[-*•]\s+/.test(t);
        var isNum    = /^\d+[.)]\s+/.test(t);
        if (isBullet) {
            if (inOl) { out.push('</ol>'); inOl = false; }
            if (!inUl) { out.push('<ul>'); inUl = true; }
            out.push('<li>' + t.replace(/^[-*•]\s+/,'') + '</li>');
        } else if (isNum) {
            if (inUl) { out.push('</ul>'); inUl = false; }
            if (!inOl) { out.push('<ol>'); inOl = true; }
            out.push('<li>' + t.replace(/^\d+[.)]\s+/,'') + '</li>');
        } else {
            if (inUl) { out.push('</ul>'); inUl = false; }
            if (inOl) { out.push('</ol>'); inOl = false; }
            out.push(t === '' ? '<br>' : '<p>' + t + '</p>');
        }
    });
    if (inUl) out.push('</ul>');
    if (inOl) out.push('</ol>');
    return out.join('');
}

// ── Modal ─────────────────────────────────────────────────────────────────────
var curId=null, selOpts=[];

function openCatalogModal(id) {
    var p=PRODUCTS.find(function(x){return x.id===id;}); if(!p) return;
    curId=id; selOpts=[];
    buildModalCarousel(p.images);
    document.getElementById('modalName').textContent=p.name;
    document.getElementById('modalPrice').textContent=fmt(p.price);

    var vsec=document.getElementById('modalVariants'); vsec.innerHTML='';
    if (p.groups && p.groups.length > 0) {
        p.groups.forEach(function(g,gi){
            var sec=document.createElement('div'); sec.className='variant-group';
            var lbl=document.createElement('p'); lbl.className='variant-group-label'; lbl.textContent=g.name;
            var opts=document.createElement('div'); opts.className='variant-opts';
            g.options.forEach(function(opt){
                var chip=document.createElement('button'); chip.className='variant-chip'; chip.textContent=opt;
                chip.onclick=function(){
                    opts.querySelectorAll('.variant-chip').forEach(function(c){c.classList.remove('active');});
                    chip.classList.add('active'); selOpts[gi]=opt; checkMatch();
                };
                opts.appendChild(chip);
            });
            sec.appendChild(lbl); sec.appendChild(opts); vsec.appendChild(sec);
        });
        setBtn(false);
    } else { setBtn(true); }

    var dw=document.getElementById('modalDescWrap');
    if (p.description && p.description.trim()) {
        document.getElementById('modalDesc').innerHTML=descToHtml(p.description); dw.style.display='block';
    } else { dw.style.display='none'; }

    document.getElementById('catalogModal').classList.add('open');
    setTimeout(function(){ document.getElementById('catalogModalSheet').classList.add('open'); },10);
    document.body.style.overflow='hidden';
}

function handleModalOverlayClick(e) {
    if (e.target===document.getElementById('catalogModal')) closeCatalogModal();
}

function checkMatch() {
    var p=PRODUCTS.find(function(x){return x.id===curId;}); if(!p||!p.groups.length) return;
    if (selOpts.length!==p.groups.length||selOpts.indexOf(undefined)!==-1) return;
    var key=selOpts.join(' - '), vd=variantsData[curId]; if(!vd) return;
    var m=(vd.variants||[]).find(function(v){return v.name===key;});
    if (m) {
        document.getElementById('modalPrice').textContent=fmt(m.price||p.price);
        if (m.image_id) {
            var img=(vd.images||[]).find(function(i){return i.id===m.image_id;});
            if (img){var idx=p.images.indexOf(img.url);if(idx!==-1)goToSlide(idx);}
        }
        setBtn(true);
    }
}

function setBtn(on) {
    var btn=document.getElementById('modalCartBtn');
    btn.disabled=!on;
    if (on) {
        btn.innerHTML='<span class="material-symbols-outlined" style="font-size:20px;">add_shopping_cart</span> Masuk Keranjang';
        btn.style.background='var(--color-primary,#001e40)'; btn.style.color='#fff'; btn.style.cursor='pointer';
    } else {
        btn.innerHTML='<span class="material-symbols-outlined" style="font-size:20px;">add_shopping_cart</span> Pilih Variasi Dulu';
        btn.style.background='#e5e7eb'; btn.style.color='#9ca3af'; btn.style.cursor='not-allowed';
    }
}

function addCurrentToCart() {
    var p=PRODUCTS.find(function(x){return x.id===curId;}); if(!p) return;
    var item={id:p.id,name:p.name,price:p.price,imageUrl:p.imageUrl,qty:1};
    if (p.groups && p.groups.length > 0) {
        var key=selOpts.join(' - '), vd=variantsData[curId];
        var m=(vd&&vd.variants||[]).find(function(v){return v.name===key;}); if(!m) return;
        item.id=p.id+'-'+m.id; item.name=p.name+' ('+m.name+')'; item.price=m.price||p.price;
        if (m.image_id){var img=(vd.images||[]).find(function(i){return i.id===m.image_id;});if(img)item.imageUrl=img.url;}
    }
    var cart=getCart(), exist=cart.find(function(i){return i.id===item.id;});
    if (exist) exist.qty++; else cart.push(item);
    saveCart(cart);
    var btn=document.getElementById('modalCartBtn'), orig=btn.innerHTML;
    btn.innerHTML='<span class="material-symbols-outlined" style="font-size:20px;">check</span> Ditambahkan!';
    btn.style.background='#16a34a';
    setTimeout(function(){btn.innerHTML=orig;btn.style.background='var(--color-primary,#001e40)';},1500);
}

function closeCatalogModal() {
    document.getElementById('catalogModalSheet').classList.remove('open');
    setTimeout(function(){document.getElementById('catalogModal').classList.remove('open');},300);
    document.body.style.overflow=''; curId=null;
}

window.openCatalogModal=openCatalogModal;
window.closeCatalogModal=closeCatalogModal;
window.addCurrentToCart=addCurrentToCart;
window.handleModalOverlayClick=handleModalOverlayClick;

// Auto-open dari URL ?open=<id>
(function(){
    var oid=parseInt(new URLSearchParams(location.search).get('open'));
    if(oid) openCatalogModal(oid);
})();
})();
</script>
