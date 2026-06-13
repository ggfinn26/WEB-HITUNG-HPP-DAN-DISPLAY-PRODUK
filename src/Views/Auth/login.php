<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full glass-card p-8 rounded-3xl shadow-xl border border-white/20 relative overflow-hidden">
        
        <!-- Decorative blobs -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-secondary/20 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent inline-block mb-2">Admin Panel</h1>
                <p class="text-slate-500 dark:text-slate-400">Masuk untuk mengelola HPP dan Order</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-sm text-center font-medium">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="?page=auth&action=loginProcess" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf ?? '') ?>">
                
                <div class="space-y-1">
                    <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email Admin</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-4 py-3 rounded-xl bg-surface-container dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 backdrop-blur-sm"
                        placeholder="admin@jastip.com">
                </div>

                <div class="space-y-1">
                    <label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-3 rounded-xl bg-surface-container dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 backdrop-blur-sm"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:opacity-90 transform hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-primary/25">
                    Masuk Sekarang
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="?page=home" class="text-sm text-slate-500 hover:text-primary transition-colors">← Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</div>
