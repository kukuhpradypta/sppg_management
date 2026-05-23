<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-6">
    <div>
        <nav class="flex text-xs text-outline mb-1 gap-2"><span>Manajemen</span><span>/</span><span class="text-primary font-medium">Data Sekolah</span></nav>
        <h2 class="text-2xl font-bold text-on-surface">Data Sekolah</h2>
        <p class="text-sm text-on-surface-variant">Kelola daftar instansi pendidikan penerima bantuan distribusi.</p>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg active:scale-95 transition-all">
        <span class="material-symbols-outlined">add</span>
        <span class="text-sm font-medium">Tambah Sekolah</span>
    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center text-primary"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Total Sekolah</p><p class="text-xl font-bold" id="stat-total"><?= number_format($totalSekolah) ?></p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-secondary/5 rounded-lg flex items-center justify-center text-secondary"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Total Siswa</p><p class="text-xl font-bold" id="stat-siswa"><?= number_format($totalSiswa) ?></p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-700"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Status Aktif</p><p class="text-xl font-bold" id="stat-aktif"><?= $totalAll > 0 ? round(($totalAktif / $totalAll) * 100, 1) : 0 ?>%</p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-error/5 rounded-lg flex items-center justify-center text-error"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">pending_actions</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Non-Aktif</p><p class="text-xl font-bold" id="stat-nonaktif"><?= $totalAll - $totalAktif ?></p></div>
    </div>
</div>

<!-- Filter -->
<div class="bg-surface-container-low p-4 rounded-t-xl border border-outline-variant border-b-0 flex flex-wrap gap-3 items-center">
    <form method="GET" action="/sekolah" class="flex gap-3 items-center">
        <div class="flex items-center gap-2 bg-white border border-outline-variant px-3 py-1 rounded-md">
            <span class="text-xs font-bold text-outline uppercase">Jenjang</span>
            <select name="jenjang" class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer" onchange="this.form.submit()">
                <option value="Semua">Semua</option>
                <option value="TK" <?= service('request')->getGet('jenjang') === 'TK' ? 'selected' : '' ?>>TK</option>
                <option value="SD" <?= service('request')->getGet('jenjang') === 'SD' ? 'selected' : '' ?>>SD</option>
                <option value="SMP" <?= service('request')->getGet('jenjang') === 'SMP' ? 'selected' : '' ?>>SMP</option>
                <option value="SMA" <?= service('request')->getGet('jenjang') === 'SMA' ? 'selected' : '' ?>>SMA</option>
            </select>
        </div>
        <div class="flex items-center gap-2 bg-white border border-outline-variant px-3 py-1 rounded-md">
            <span class="text-xs font-bold text-outline uppercase">Status</span>
            <select name="status" class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer" onchange="this.form.submit()">
                <option value="Semua">Semua</option>
                <option value="Aktif" <?= service('request')->getGet('status') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Non-Aktif" <?= service('request')->getGet('status') === 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
            </select>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white border border-outline-variant rounded-b-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Nama Sekolah</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Alamat</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Jenjang</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Jumlah Siswa</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-outline-variant/50">
                <?php foreach ($sekolahList as $s): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs"><?= substr($s['jenjang'], 0, 2) ?></div><span class="font-medium text-sm"><?= esc($s['nama_sekolah']) ?></span></div></td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant max-w-xs truncate"><?= esc($s['alamat']) ?></td>
                    <td class="px-5 py-3"><span class="bg-surface-container-high px-2 py-1 rounded text-[10px] font-bold"><?= $s['jenjang'] ?></span></td>
                    <td class="px-5 py-3 text-sm"><?= number_format($s['jumlah_siswa']) ?></td>
                    <td class="px-5 py-3 text-center"><?= $s['is_active'] ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>' : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>' ?></td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="editData(<?= $s['id'] ?>)" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button>
                            <a href="/sekolah/delete/<?= $s['id'] ?>" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="return confirm('Yakin hapus?')"><span class="material-symbols-outlined text-lg">delete</span></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($sekolahList)): ?>
                <tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data sekolah.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-outline-variant"><?= $pager->links() ?></div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 bg-primary text-on-primary flex items-center justify-between">
            <h3 class="text-lg font-semibold" id="modal-title">Tambah Sekolah Baru</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-data" action="/sekolah/store" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Nama Sekolah</label>
                <input type="text" name="nama_sekolah" id="f_nama" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Masukkan nama sekolah..."/>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea name="alamat" id="f_alamat" rows="2" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none" placeholder="Alamat lengkap..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Jenjang</label>
                    <select name="jenjang" id="f_jenjang" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                        <option value="">Pilih</option><option value="TK">TK</option><option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA">SMA</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa" id="f_siswa" required min="0" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="0"/>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg">
                <div><p class="font-medium text-sm">Status Aktif</p><p class="text-xs text-on-surface-variant">Menerima distribusi bantuan</p></div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="f_active" value="1" checked class="sr-only peer"/>
                    <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                </label>
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-5 py-2 text-outline font-semibold hover:bg-surface-container-high rounded-lg">Batal</button>
                <button type="submit" class="px-6 py-2 bg-primary text-on-primary font-semibold rounded-lg hover:bg-primary-container active:scale-95 transition-all shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
    document.getElementById('modal-title').textContent = 'Tambah Sekolah Baru';
    document.getElementById('form-data').action = '/sekolah/store';
    document.getElementById('f_nama').value = '';
    document.getElementById('f_alamat').value = '';
    document.getElementById('f_jenjang').value = '';
    document.getElementById('f_siswa').value = '';
    document.getElementById('f_active').checked = true;
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
function editData(id) {
    fetch('/sekolah/get/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modal-title').textContent = 'Edit Sekolah';
            document.getElementById('form-data').action = '/sekolah/update/' + id;
            document.getElementById('f_nama').value = data.nama_sekolah;
            document.getElementById('f_alamat').value = data.alamat;
            document.getElementById('f_jenjang').value = data.jenjang;
            document.getElementById('f_siswa').value = data.jumlah_siswa;
            document.getElementById('f_active').checked = (data.is_active == 1);
        });
}
document.getElementById('modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });

// AJAX form submit - no page refresh
document.getElementById('form-data').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    if (!form.querySelector('[name="is_active"]').checked) {
        formData.set('is_active', '0');
    }
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(res => {
        closeModal();
        if (res.success) {
            showToast(res.message, 'success');
            refreshTable();
        } else if (res.errors) {
            showToast(Object.values(res.errors).join(', '), 'error');
        }
    });
});

function refreshTable() {
    fetch('/sekolah/data', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('table-body');
            if (res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data sekolah.</td></tr>';
            } else {
                tbody.innerHTML = res.data.map(s => `
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        <td class="px-5 py-3"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">${s.jenjang.substring(0,2)}</div><span class="font-medium text-sm">${s.nama_sekolah}</span></div></td>
                        <td class="px-5 py-3 text-sm text-on-surface-variant max-w-xs truncate">${s.alamat}</td>
                        <td class="px-5 py-3"><span class="bg-surface-container-high px-2 py-1 rounded text-[10px] font-bold">${s.jenjang}</span></td>
                        <td class="px-5 py-3 text-sm">${Number(s.jumlah_siswa).toLocaleString('id-ID')}</td>
                        <td class="px-5 py-3 text-center">${s.is_active == 1 ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>' : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>'}</td>
                        <td class="px-5 py-3 text-right"><div class="flex justify-end gap-1"><button onclick="editData(${s.id})" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button><a href="/sekolah/delete/${s.id}" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="return confirm('Yakin hapus?')"><span class="material-symbols-outlined text-lg">delete</span></a></div></td>
                    </tr>
                `).join('');
            }
            // Update stats
            document.getElementById('stat-total').textContent = Number(res.stats.totalSekolah).toLocaleString('id-ID');
            document.getElementById('stat-siswa').textContent = Number(res.stats.totalSiswa).toLocaleString('id-ID');
            document.getElementById('stat-aktif').textContent = res.stats.totalAll > 0 ? Math.round((res.stats.totalAktif / res.stats.totalAll) * 1000) / 10 + '%' : '0%';
            document.getElementById('stat-nonaktif').textContent = res.stats.totalAll - res.stats.totalAktif;
        });
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border rounded-xl shadow-lg transition-all duration-300 ' + (type === 'success' ? 'border-green-200' : 'border-red-200');
    toast.innerHTML = `<div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 ${type === 'success' ? 'bg-green-100' : 'bg-red-100'}"><span class="material-symbols-outlined text-lg ${type === 'success' ? 'text-green-600' : 'text-red-600'}">${type === 'success' ? 'check_circle' : 'error'}</span></div><p class="text-sm font-medium text-gray-800">${msg}</p>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
<?= $this->endSection() ?>
