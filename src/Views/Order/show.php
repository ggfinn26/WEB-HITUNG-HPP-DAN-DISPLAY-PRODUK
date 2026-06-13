<div class="mb-8 flex items-center justify-between gap-4 relative z-10">
    <div class="flex items-center gap-4">
        <a href="?page=orders" class="p-3 rounded-2xl bg-surface border border-outline-variant text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-black text-text tracking-tight">Detail Order</h1>
            <p class="text-muted text-sm mt-1">Rincian pesanan dan manajemen status</p>
        </div>
    </div>
    <a href="?page=orders&action=editDetail&id=<?= urlencode($order->getOrderNumber()) ?>"
       class="flex items-center gap-2 px-5 py-2.5 bg-surface border border-outline-variant rounded-2xl text-sm font-bold text-on-surface hover:border-primary hover:text-primary transition-all shadow-sm no-print">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        Edit Detail
    </a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl mb-6 relative z-10">
        <?= htmlspecialchars($_SESSION['success_message']) ?><?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
    
    <!-- Informasi Pemesan -->
    <div class="lg:col-span-1 space-y-6">
        <div class="glass p-8 rounded-3xl shadow-xl border-t-4 border-t-secondary relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-secondary opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            
            <h2 class="text-xs font-bold text-muted uppercase tracking-widest mb-6">Informasi Pelanggan</h2>
            
            <div class="flex items-center gap-5 mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-secondary to-primary text-white flex items-center justify-center text-3xl font-black shadow-lg">
                    <?php echo strtoupper(substr($order->getNamaPemesan(), 0, 1)); ?>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-text leading-tight"><?php echo htmlspecialchars($order->getNamaPemesan()); ?></h3>
                    <?php if($order->getInstagramUserNamePemesan()): ?>
                    <a href="https://instagram.com/<?php echo htmlspecialchars($order->getInstagramUserNamePemesan()); ?>" target="_blank" class="text-sm text-pink-500 font-medium mt-1 hover:underline inline-flex items-center gap-1">@<?php echo htmlspecialchars($order->getInstagramUserNamePemesan()); ?> ↗</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-5 text-sm">
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order->getWhatsappPemesan()); ?>" target="_blank" class="flex items-start gap-4 p-4 rounded-2xl bg-surface border border-outline-variant hover:border-green-500 hover:shadow-md transition-all group">
                    <div class="p-2 rounded-xl bg-green-100 text-green-600 group-hover:bg-green-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-1">WhatsApp</p>
                        <p class="text-text font-medium group-hover:text-green-600 transition-colors"><?php echo htmlspecialchars($order->getWhatsappPemesan()); ?> ↗</p>
                    </div>
                </a>
                
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-surface border border-outline-variant">
                    <div class="p-2 rounded-xl bg-blue-100 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-text font-medium leading-relaxed"><?php echo nl2br(htmlspecialchars($order->getAlamatPemesan())); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Order & Tagihan -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass p-8 md:p-10 rounded-3xl shadow-xl relative overflow-hidden border-t-4 border-t-primary">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary opacity-5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <div>
                    <h2 class="text-xs font-bold text-muted uppercase tracking-widest mb-2">Nomor Seri Order</h2>
                    <div class="inline-block px-4 py-2 bg-primary/10 rounded-xl border border-primary/20 text-primary font-mono font-bold text-xl tracking-tight">
                        <?php echo htmlspecialchars($order->getOrderNumber()); ?>
                    </div>
                </div>
                
                <div class="text-left md:text-right">
                    <p class="text-xs font-bold text-muted uppercase tracking-widest mb-1">Tanggal & Waktu Pesanan</p>
                    <p class="font-bold text-text text-lg"><?php echo $order->getCreatedAt()->format('d M Y, H:i'); ?></p>
                </div>
            </div>

            <!-- Form Update Status -->
            <div class="bg-surface p-6 rounded-3xl border border-outline-variant mb-10 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-bold text-muted uppercase tracking-widest mb-2">Status Saat Ini</p>
                    <?php 
                        $status = $order->getOrderStatus(); 
                        $statusLower = strtolower($status);
                        
                        $historyStr = $order->getStatusHistory();
                        $historyArr = $historyStr ? json_decode($historyStr, true) : [];
                        $lastItem = !empty($historyArr) ? end($historyArr) : null;
                        $savedColor = $lastItem['color'] ?? null;
                        
                        $statusColor = 'text-blue-600 bg-blue-100';
                        $dotColor = 'bg-blue-500';
                        $isPulse = false;
                        
                        if ($savedColor) {
                            if ($savedColor === 'amber') {
                                $statusColor = 'text-amber-600 bg-amber-100';
                                $dotColor = 'bg-amber-500';
                                $isPulse = true;
                            } elseif ($savedColor === 'green') {
                                $statusColor = 'text-green-600 bg-green-100';
                                $dotColor = 'bg-green-500';
                            } elseif ($savedColor === 'red') {
                                $statusColor = 'text-red-600 bg-red-100';
                                $dotColor = 'bg-red-500';
                            }
                        } else {
                            if (str_contains($statusLower, 'pending') || str_contains($statusLower, 'tunggu')) {
                                $statusColor = 'text-amber-600 bg-amber-100';
                                $dotColor = 'bg-amber-500';
                                $isPulse = true;
                            } elseif (str_contains($statusLower, 'selesai') || str_contains($statusLower, 'sukses')) {
                                $statusColor = 'text-green-600 bg-green-100';
                                $dotColor = 'bg-green-500';
                            } elseif (str_contains($statusLower, 'batal') || str_contains($statusLower, 'tolak') || str_contains($statusLower, 'refund')) {
                                $statusColor = 'text-red-600 bg-red-100';
                                $dotColor = 'bg-red-500';
                            }
                        }
                    ?>
                    <div class="inline-flex flex-wrap items-center gap-2 mt-2">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl <?php echo $statusColor; ?> font-bold">
                            <span class="relative flex h-3 w-3">
                                <?php if($isPulse): ?>
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo $dotColor; ?> opacity-75"></span>
                                <?php endif; ?>
                                <span class="relative inline-flex rounded-full h-3 w-3 <?php echo $dotColor; ?>"></span>
                            </span>
                            <?php echo htmlspecialchars($status); ?>
                        </div>
                        
                        <?php 
                            $waMessage = "Halo! Status pesanan Anda (Order ID: " . $order->getOrderNumber() . ") saat ini adalah: *" . $status . "*.\n\n";
                            if (!empty($lastItem['detail'])) {
                                $waMessage .= "Catatan: " . $lastItem['detail'] . "\n\n";
                            }
                            $waMessage .= "Terima kasih!";
                            $waNumber = "62895380123352";
                            $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMessage);
                        ?>
                        <a href="<?php echo $waUrl; ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold transition-colors shadow-sm text-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Kirim ke Pemesan
                        </a>
                    </div>
                </div>
                
                <form action="?page=orders&action=update&id=<?php echo urlencode($order->getOrderNumber()); ?>" method="POST" class="flex flex-col w-full sm:w-auto gap-3 p-4 bg-background rounded-2xl border border-outline-variant no-print shadow-sm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Helper\CsrfHelper::getToken()) ?>">
                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full">
                        <select name="statusColor" class="px-3 py-2 rounded-xl bg-surface border border-outline-variant/30 text-text outline-none focus:ring-2 focus:ring-primary/20 text-sm font-semibold">
                            <option value="blue" <?php echo ($savedColor === 'blue') ? 'selected' : ''; ?>>🔵 Biru (Info)</option>
                            <option value="amber" <?php echo ($savedColor === 'amber') ? 'selected' : ''; ?>>🟠 Kuning (Proses)</option>
                            <option value="green" <?php echo ($savedColor === 'green') ? 'selected' : ''; ?>>🟢 Hijau (Selesai)</option>
                            <option value="red" <?php echo ($savedColor === 'red') ? 'selected' : ''; ?>>🔴 Merah (Gagal)</option>
                        </select>
                        <input list="statusOptions" name="orderStatus" value="<?php echo htmlspecialchars($status); ?>" placeholder="Pilih/Ketik Status..." class="flex-grow px-4 py-2 rounded-xl border-none bg-transparent font-semibold text-text outline-none focus:ring-2 focus:ring-primary/20 w-full sm:w-auto">
                        <datalist id="statusOptions">
                            <option value="Pending">
                            <option value="Dikonfirmasi">
                            <option value="Dalam Perjalanan">
                            <option value="Sudah Tiba">
                            <option value="Selesai">
                            <option value="Dibatalkan">
                            <option value="Ajukan Refund">
                            <option value="Refund Pending">
                            <option value="Refund Success">
                        </datalist>
                        <button type="submit" class="px-6 py-2 bg-text text-surface font-bold rounded-xl hover:opacity-90 transition-all shadow-md">
                            Update
                        </button>
                    </div>
                    <div class="w-full">
                        <input type="text" name="statusDetail" placeholder="Catatan/Detail Status (opsional)..." class="w-full px-4 py-2 rounded-xl border border-outline-variant/30 bg-transparent font-medium text-sm text-text outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </form>
            </div>

            <!-- Daftar Barang Pesanan -->
            <?php 
                $itemsJson = $order->getListItemOrder();
                $items = $itemsJson ? json_decode($itemsJson, true) : [];
                if (!empty($items) && is_array($items)):
            ?>
            <div class="mb-10 bg-surface rounded-2xl border border-outline-variant overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant bg-background/50">
                    <h3 class="font-bold text-text flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Daftar Barang Pesanan
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-background text-muted">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama Barang</th>
                                <th class="px-6 py-3 font-semibold text-right">Harga Satuan</th>
                                <th class="px-6 py-3 font-semibold text-center">Qty</th>
                                <th class="px-6 py-3 font-semibold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-background/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-text"><?php echo htmlspecialchars($item['name'] ?? '-'); ?></td>
                                <td class="px-6 py-4 text-right text-muted">Rp. <?php echo number_format((float)($item['price'] ?? 0), 0, ',', '.'); ?></td>
                                <td class="px-6 py-4 text-center font-bold"><?php echo (int)($item['qty'] ?? 1); ?></td>
                                <td class="px-6 py-4 text-right font-bold text-text">Rp. <?php echo number_format((float)(($item['price'] ?? 0) * ($item['qty'] ?? 1)), 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Invoice Total -->
            <div class="p-8 rounded-3xl bg-gradient-to-br from-primary to-secondary text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-surface opacity-10 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6 relative z-10">
                    <div>
                        <p class="text-white/80 font-semibold uppercase tracking-widest text-sm mb-2">Total Tagihan Pemesan</p>
                        <h3 class="text-4xl md:text-5xl font-black tracking-tight drop-shadow-sm">
                            <span class="text-2xl font-bold opacity-80 mr-1">Rp.</span><?php echo number_format((float)$order->getSubTotal(), 0, ',', '.'); ?>
                        </h3>
                    </div>
                    <a href="?page=orders&action=print&id=<?php echo urlencode($order->getOrderNumber()); ?>" target="_blank" class="w-14 h-14 rounded-2xl bg-surface-container-low backdrop-blur-sm border border-white/30 flex items-center justify-center hover:bg-surface-container-low transition-colors shadow-lg no-print group">
                        <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        </div>

        <!-- Timeline Riwayat Status -->
        <?php 
        $historyStr = $order->getStatusHistory();
        $history = $historyStr ? json_decode($historyStr, true) : [];
        if (!empty($history)): 
        ?>
        <div class="glass p-8 md:p-10 rounded-3xl shadow-lg border border-outline-variant mt-6">
            <h2 class="text-xl font-bold text-text mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Status Order
            </h2>
            <div class="relative border-l-2 border-outline-variant ml-3 space-y-6">
                <?php foreach (array_reverse($history) as $index => $item): 
                    $dotColorClass = 'bg-blue-500';
                    $itemColor = $item['color'] ?? 'blue';
                    if ($itemColor === 'red') $dotColorClass = 'bg-red-500';
                    elseif ($itemColor === 'green') $dotColorClass = 'bg-green-500';
                    elseif ($itemColor === 'amber') $dotColorClass = 'bg-amber-500';
                    
                    if ($index !== 0) $dotColorClass = 'bg-slate-300 dark:bg-slate-600'; // Gray out older items optionally, or keep their colors. Let's keep the actual status color but maybe slightly dimmed.
                    
                    if ($index !== 0) {
                        if ($itemColor === 'red') $dotColorClass = 'bg-red-300';
                        elseif ($itemColor === 'green') $dotColorClass = 'bg-green-300';
                        elseif ($itemColor === 'amber') $dotColorClass = 'bg-amber-300';
                        elseif ($itemColor === 'blue') $dotColorClass = 'bg-blue-300';
                    }
                ?>
                <div class="relative pl-6">
                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full <?php echo $dotColorClass; ?> border-4 border-surface shadow-sm"></div>
                    <div class="bg-surface/50 p-4 rounded-2xl border border-outline-variant">
                        <p class="font-bold text-text text-lg"><?php echo htmlspecialchars($item['status']); ?></p>
                        
                        <?php if(!empty($item['detail'])): ?>
                            <p class="text-sm text-text/80 mt-2 p-3 bg-background rounded-xl border border-outline-variant/20 italic">
                                "<?php echo htmlspecialchars($item['detail']); ?>"
                            </p>
                        <?php endif; ?>
                        
                        <p class="text-xs text-muted font-medium mt-2">
                            <?php 
                                $dt = new \DateTime($item['datetime']); 
                                echo $dt->format('d M Y, H:i');
                            ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
