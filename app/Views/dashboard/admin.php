<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-on-surface">Dashboard Monitoring</h2>
    <p class="text-on-surface-variant text-sm">Ringkasan operasional distribusi makanan harian seluruh SPPG.</p>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow" data-hover>
        <div class="flex justify-between items-start mb-2">
            <span class="w-10 h-10 bg-blue-50 text-blue-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
            </span>
        </div>
        <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Total Sekolah</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($totalSekolah) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow" data-hover>
        <div class="flex justify-between items-start mb-2">
            <span class="w-10 h-10 bg-emerald-50 text-emerald-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">assignment_turned_in</span>
            </span>
        </div>
        <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Total SPPG</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($totalSppg) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow" data-hover>
        <div class="flex justify-between items-start mb-2">
            <span class="w-10 h-10 bg-orange-50 text-orange-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
            </span>
        </div>
        <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Distribusi Hari Ini</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($todayStats['total_distribusi'] ?? 0) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow" data-hover>
        <div class="flex justify-between items-start mb-2">
            <span class="w-10 h-10 bg-purple-50 text-purple-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">restaurant</span>
            </span>
        </div>
        <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Total Porsi</h3>
        <p class="text-2xl font-bold text-primary mt-1"><?= number_format($todayStats['total_porsi'] ?? 0) ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow" data-hover>
        <div class="flex justify-between items-start mb-2">
            <span class="w-10 h-10 bg-amber-50 text-amber-700 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
            </span>
        </div>
        <h3 class="text-on-surface-variant text-xs font-bold uppercase tracking-wider">Estimasi Biaya</h3>
        <p class="text-lg font-bold text-primary mt-1">Rp <?= number_format($todayStats['total_biaya'] ?? 0, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Weekly Distribution Chart -->
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm">
        <h3 class="font-semibold text-on-surface mb-4">Distribusi 7 Hari Terakhir</h3>
        <div class="flex items-end justify-between gap-2 h-[200px] px-2">
            <?php
            $maxPorsi = max(array_column($weeklyData, 'total_porsi')) ?: 1;
            foreach ($weeklyData as $day):
                $height = ($day['total_porsi'] / $maxPorsi) * 100;
            ?>
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-[10px] font-bold text-primary"><?= $day['total_porsi'] ?></span>
                <div class="w-full bg-primary/80 rounded-t-lg transition-all hover:bg-primary" style="height: <?= max($height, 5) ?>%"></div>
                <span class="text-[10px] text-on-surface-variant font-medium"><?= $day['date'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Top Sekolah -->
    <div class="bg-white p-5 rounded-xl border border-outline-variant shadow-sm">
        <h3 class="font-semibold text-on-surface mb-4">Top Sekolah Distribusi</h3>
        <div class="flex flex-col gap-3">
            <?php
            $maxTopPorsi = !empty($topSekolah) ? (int)$topSekolah[0]['total_porsi'] : 1;
            foreach ($topSekolah as $sekolah):
                $width = ((int)$sekolah['total_porsi'] / $maxTopPorsi) * 100;
            ?>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium"><?= esc($sekolah['nama_sekolah']) ?></span>
                    <span class="text-on-surface-variant"><?= number_format($sekolah['total_porsi']) ?> Porsi</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                    <div class="bg-primary h-full rounded-full" style="width: <?= $width ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($topSekolah)): ?>
            <p class="text-sm text-on-surface-variant text-center py-4">Belum ada data distribusi bulan ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Distribution Table -->
<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-outline-variant flex justify-between items-center">
        <h3 class="font-semibold text-on-surface">Distribusi Terbaru</h3>
        <a href="/distribusi" class="text-primary text-sm font-medium hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">SPPG</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Sekolah</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Porsi</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
                <?php foreach ($recentDistribusi as $dist): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3 text-sm"><?= date('d/m/Y', strtotime($dist['tanggal_distribusi'])) ?></td>
                    <td class="px-5 py-3 text-sm font-medium"><?= esc($dist['nama_sppg']) ?></td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant"><?= esc($dist['nama_sekolah']) ?></td>
                    <td class="px-5 py-3 text-sm text-right"><?= number_format($dist['jumlah_porsi']) ?></td>
                    <td class="px-5 py-3">
                        <?php
                        $statusColors = [
                            'preparing'  => 'bg-blue-100 text-blue-700',
                            'in_transit' => 'bg-orange-100 text-orange-700',
                            'delivered'  => 'bg-green-100 text-green-700',
                            'cancelled'  => 'bg-red-100 text-red-700',
                        ];
                        $color = $statusColors[$dist['status']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-2 py-1 <?= $color ?> rounded-full text-[10px] font-bold uppercase"><?= strtoupper($dist['status']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentDistribusi)): ?>
                <tr><td colspan="5" class="px-5 py-8 text-center text-on-surface-variant">Belum ada data distribusi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Menu & SPPG Aktif -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Menu Hari Ini -->
    <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-5">
        <h3 class="font-semibold text-on-surface mb-4">Menu Hari Ini</h3>
        <div class="space-y-3">
            <?php foreach ($todayMenu as $menu): ?>
            <div class="p-3 bg-surface-container-low rounded-lg border border-outline-variant">
                <p class="text-sm font-bold text-primary"><?= esc($menu['nama_menu']) ?></p>
                <p class="text-xs text-on-surface-variant mt-1"><?= esc($menu['deskripsi'] ?? '-') ?></p>
                <p class="text-xs text-on-surface-variant mt-1">Harga: Rp <?= number_format($menu['estimasi_harga_per_porsi'], 0, ',', '.') ?>/porsi</p>
                <p class="text-[10px] text-outline mt-1"><?= esc($menu['nama_sppg']) ?></p>
            </div>
            <?php endforeach; ?>
            <?php if (empty($todayMenu)): ?>
            <p class="text-sm text-on-surface-variant text-center py-4">Belum ada menu hari ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- SPPG Aktif -->
    <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-5">
        <h3 class="font-semibold text-on-surface mb-4">SPPG Aktif</h3>
        <div class="space-y-3">
            <?php foreach ($activeSppg as $sppg): ?>
            <div class="flex items-center gap-3 p-2">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-on-surface"><?= esc($sppg['nama_sppg']) ?></p>
                    <p class="text-[10px] text-on-surface-variant">PJ: <?= esc($sppg['penanggung_jawab']) ?></p>
                </div>
                <span class="material-symbols-outlined text-green-600 text-lg">check_circle</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
