<aside class="w-[280px] h-screen fixed left-0 top-0 bg-primary shadow-sm flex flex-col py-6 z-50">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-secondary-fixed rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">restaurant</span>
            </div>
            <div>
                <h1 class="text-on-primary font-bold text-xl leading-tight">SPPG</h1>
                <p class="text-on-primary/60 text-[10px] uppercase tracking-widest">Management</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 flex flex-col gap-1">
        <?php $currentUrl = current_url(); ?>

        <a class="flex items-center gap-3 px-6 py-3 <?= strpos($currentUrl, 'dashboard') !== false ? 'text-secondary-fixed border-l-4 border-secondary-fixed bg-white/5' : 'text-on-primary/70 hover:text-on-primary hover:bg-white/5' ?> transition-all" href="/dashboard">
            <span class="material-symbols-outlined" <?= strpos($currentUrl, 'dashboard') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>dashboard</span>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <?php if (session()->get('role') === 'admin'): ?>
        <a class="flex items-center gap-3 px-6 py-3 <?= strpos($currentUrl, 'sekolah') !== false ? 'text-secondary-fixed border-l-4 border-secondary-fixed bg-white/5' : 'text-on-primary/70 hover:text-on-primary hover:bg-white/5' ?> transition-all" href="/sekolah">
            <span class="material-symbols-outlined">school</span>
            <span class="text-sm font-medium">Sekolah</span>
        </a>

        <a class="flex items-center gap-3 px-6 py-3 <?= strpos($currentUrl, '/sppg') !== false ? 'text-secondary-fixed border-l-4 border-secondary-fixed bg-white/5' : 'text-on-primary/70 hover:text-on-primary hover:bg-white/5' ?> transition-all" href="/sppg">
            <span class="material-symbols-outlined">assignment</span>
            <span class="text-sm font-medium">SPPG</span>
        </a>
        <?php endif; ?>

        <a class="flex items-center gap-3 px-6 py-3 <?= strpos($currentUrl, 'menu') !== false ? 'text-secondary-fixed border-l-4 border-secondary-fixed bg-white/5' : 'text-on-primary/70 hover:text-on-primary hover:bg-white/5' ?> transition-all" href="/menu">
            <span class="material-symbols-outlined">restaurant_menu</span>
            <span class="text-sm font-medium">Menu</span>
        </a>

        <a class="flex items-center gap-3 px-6 py-3 <?= strpos($currentUrl, 'distribusi') !== false ? 'text-secondary-fixed border-l-4 border-secondary-fixed bg-white/5' : 'text-on-primary/70 hover:text-on-primary hover:bg-white/5' ?> transition-all" href="/distribusi">
            <span class="material-symbols-outlined">local_shipping</span>
            <span class="text-sm font-medium">Distribusi</span>
        </a>
    </nav>

    <div class="mt-auto px-6 pt-6 border-t border-on-primary/10">
        <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl">
            <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center text-primary font-bold">
                <?= strtoupper(substr(session()->get('nama') ?? 'U', 0, 1)) ?>
            </div>
            <div class="overflow-hidden flex-1">
                <p class="text-on-primary text-sm font-medium truncate"><?= esc(session()->get('nama') ?? 'User') ?></p>
                <p class="text-on-primary/50 text-xs truncate capitalize"><?= esc(session()->get('role') ?? '') ?></p>
            </div>
            <a href="/logout" class="text-on-primary/50 hover:text-on-primary" title="Logout">
                <span class="material-symbols-outlined text-lg">logout</span>
            </a>
        </div>
    </div>
</aside>
