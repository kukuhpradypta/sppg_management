<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-on-surface">Dashboard SPPG</h2>
    <p class="text-on-surface-variant text-sm">Selamat datang, <?= esc($sppgInfo['nama_sppg'] ?? 'SPPG') ?></p>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm" data-hover>
        <span class="w-10 h-10 bg-orange-50 text-orange-700 rounded-lg flex items-center justify-center mb-2">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
        </span>
        <h3 class="text-xs font-bold text-on-surface-variant uppercase">Distribusi Hari Ini</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($todayStats['total_distribusi'] ?? 0) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm" data-hover>
        <span class="w-10 h-10 bg-purple-50 text-purple-700 rounded-lg flex items-center justify-center mb-2">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">restaurant</span>
        </span>
        <h3 class="text-xs font-bold text-on-surface-variant uppercase">Total Porsi</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($todayStats['total_porsi'] ?? 0) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm" data-hover>
        <span class="w-10 h-10 bg-amber-50 text-amber-700 rounded-lg flex items-center justify-center mb-2">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
        </span>
        <h3 class="text-xs font-bold text-on-surface-variant uppercase">Estimasi Biaya</h3>
        <p class="text-lg font-bold text-primary mt-1">Rp <?= number_format($todayStats['total_biaya'] ?? 0, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Quick Actions -->
<div class="flex gap-3 mb-6">
    <a href="/distribusi" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg transition-all">
        <span class="material-symbols-outlined">local_shipping</span>
        Tambah Distribusi
    </a>
    <a href="/menu" class="flex items-center gap-2 bg-white border border-outline-variant text-primary px-5 py-2 rounded-lg hover:shadow-md transition-all">
        <span class="material-symbols-outlined">restaurant_menu</span>
        Tambah Menu
    </a>
</div>

<!-- Recent & Menu -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Recent Distribusi -->
    <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="p-5 border-b border-outline-variant">
            <h3 class="font-semibold text-on-surface">Distribusi Terbaru</h3>
        </div>
        <div class="divide-y divide-outline-variant/50">
            <?php foreach ($recentDistribusi as $dist): ?>
            <div class="px-5 py-3 flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium"><?= esc($dist['nama_sekolah']) ?></p>
                    <p class="text-xs text-on-surface-variant"><?= date('d/m/Y', strtotime($dist['tanggal_distribusi'])) ?> - <?= number_format($dist['jumlah_porsi']) ?> porsi</p>
                </div>
                <?php
                $statusColors = ['preparing' => 'bg-blue-100 text-blue-700', 'in_transit' => 'bg-orange-100 text-orange-700', 'delivered' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
                $color = $statusColors[$dist['status']] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="px-2 py-1 <?= $color ?> rounded-full text-[10px] font-bold uppercase"><?= $dist['status'] ?></span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($recentDistribusi)): ?>
            <p class="px-5 py-8 text-center text-on-surface-variant text-sm">Belum ada distribusi hari ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Menu Hari Ini -->
    <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-5">
        <h3 class="font-semibold text-on-surface mb-4">Menu Hari Ini</h3>
        <div class="space-y-3">
            <?php foreach ($todayMenu as $menu): ?>
            <div class="p-3 bg-surface-container-low rounded-lg border border-outline-variant">
                <p class="text-sm font-bold text-primary"><?= esc($menu['nama_menu']) ?></p>
                <p class="text-xs text-on-surface-variant mt-1"><?= esc($menu['deskripsi'] ?? '-') ?></p>
                <p class="text-xs text-on-surface-variant mt-1">Rp <?= number_format($menu['estimasi_harga_per_porsi'], 0, ',', '.') ?>/porsi</p>
            </div>
            <?php endforeach; ?>
            <?php if (empty($todayMenu)): ?>
            <p class="text-sm text-on-surface-variant text-center py-4">Belum ada menu hari ini. <a href="/menu" class="text-primary hover:underline">Tambah menu</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
