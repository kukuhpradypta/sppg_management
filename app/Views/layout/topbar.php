<header class="h-[64px] fixed top-0 right-0 left-[280px] z-40 bg-surface border-b border-outline-variant flex justify-between items-center px-6">
    <div class="flex items-center gap-4">
        <h2 class="text-lg font-semibold text-on-surface"><?= $title ?? 'Dashboard' ?></h2>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-on-surface-variant"><?= date('d M Y') ?></span>
        <div class="h-6 w-px bg-outline-variant"></div>
        <a href="/logout" class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-error transition-colors">
            <span class="material-symbols-outlined text-lg">logout</span>
            Logout
        </a>
    </div>
</header>
