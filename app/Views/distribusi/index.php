<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-6">
    <div>
        <h2 class="text-2xl font-bold text-on-surface">Data Distribusi</h2>
        <p class="text-sm text-on-surface-variant">Riwayat distribusi makanan ke sekolah.</p>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg active:scale-95 transition-all">
        <span class="material-symbols-outlined">add</span>
        <span class="text-sm font-medium">Input Distribusi</span>
    </button>
</div>

<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">SPPG</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Sekolah</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Menu</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Porsi</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Total Biaya</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-outline-variant/50">
                <?php foreach ($distribusiList as $d): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3 text-sm"><?= date('d/m/Y', strtotime($d['tanggal_distribusi'])) ?></td>
                    <td class="px-5 py-3 text-sm font-medium"><?= esc($d['nama_sppg']) ?></td>
                    <td class="px-5 py-3 text-sm"><?= esc($d['nama_sekolah']) ?></td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant max-w-[150px] truncate"><?= esc($d['nama_menu']) ?></td>
                    <td class="px-5 py-3 text-sm text-right"><?= number_format($d['jumlah_porsi']) ?></td>
                    <td class="px-5 py-3 text-sm text-right">Rp <?= number_format($d['estimasi_total_biaya'], 0, ',', '.') ?></td>
                    <td class="px-5 py-3 text-center">
                        <?php $sc = ['preparing'=>'bg-blue-100 text-blue-700','in_transit'=>'bg-orange-100 text-orange-700','delivered'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-700']; ?>
                        <span class="px-2 py-1 <?= $sc[$d['status']] ?? 'bg-gray-100 text-gray-700' ?> rounded-full text-[10px] font-bold uppercase"><?= strtoupper(str_replace('_',' ',$d['status'])) ?></span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="editData(<?= $d['id'] ?>)" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button>
                            <?php if (session()->get('role') === 'admin'): ?>
                            <a href="/distribusi/delete/<?= $d['id'] ?>" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="return confirm('Yakin hapus?')"><span class="material-symbols-outlined text-lg">delete</span></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($distribusiList)): ?>
                <tr><td colspan="8" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data distribusi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-outline-variant"><?= $pager->links() ?></div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 bg-primary text-on-primary flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-lg font-semibold" id="modal-title">Input Distribusi</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-data" action="/distribusi/store" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" name="tanggal_distribusi" id="f_tanggal" required value="<?= date('Y-m-d') ?>" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none"/>
                </div>
                <?php if (session()->get('role') === 'admin'): ?>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">SPPG</label>
                    <select name="sppg_id" id="f_sppg" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                        <option value="">Pilih SPPG</option>
                        <?php foreach ($sppgList as $sppg): ?>
                        <option value="<?= $sppg['id'] ?>"><?= esc($sppg['nama_sppg']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Sekolah Tujuan</label>
                <select name="sekolah_id" id="f_sekolah" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Pilih Sekolah</option>
                    <?php foreach ($sekolahList as $sk): ?>
                    <option value="<?= $sk['id'] ?>"><?= esc($sk['nama_sekolah']) ?> (<?= $sk['jenjang'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Menu</label>
                <select name="menu_id" id="f_menu" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Pilih Menu</option>
                    <?php foreach ($menuList as $mn): ?>
                    <option value="<?= $mn['id'] ?>" data-harga="<?= $mn['estimasi_harga_per_porsi'] ?>"><?= esc($mn['nama_menu']) ?> - Rp <?= number_format($mn['estimasi_harga_per_porsi'], 0, ',', '.') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Jumlah Porsi</label>
                    <input type="number" name="jumlah_porsi" id="f_porsi" required min="1" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="0"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Estimasi Biaya</label>
                    <div class="w-full border border-outline-variant rounded-lg p-3 bg-surface-container-low font-bold text-sm" id="f_biaya">Rp 0</div>
                </div>
            </div>
            <div id="status-wrap" class="hidden">
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Status</label>
                <select name="status" id="f_status" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                    <option value="preparing">Preparing</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Catatan</label>
                <textarea name="catatan" id="f_catatan" rows="2" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Opsional..."></textarea>
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
function calcBiaya() {
    const sel = document.getElementById('f_menu');
    const opt = sel.options[sel.selectedIndex];
    const harga = parseFloat(opt?.getAttribute('data-harga')) || 0;
    const porsi = parseInt(document.getElementById('f_porsi').value) || 0;
    document.getElementById('f_biaya').textContent = 'Rp ' + (harga * porsi).toLocaleString('id-ID');
}
document.getElementById('f_menu').addEventListener('change', calcBiaya);
document.getElementById('f_porsi').addEventListener('input', calcBiaya);

function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
    document.getElementById('modal-title').textContent = 'Input Distribusi';
    document.getElementById('form-data').action = '/distribusi/store';
    document.getElementById('f_tanggal').value = '<?= date('Y-m-d') ?>';
    document.getElementById('f_sekolah').value = '';
    document.getElementById('f_menu').value = '';
    document.getElementById('f_porsi').value = '';
    document.getElementById('f_catatan').value = '';
    document.getElementById('f_biaya').textContent = 'Rp 0';
    document.getElementById('status-wrap').classList.add('hidden');
    <?php if (session()->get('role') === 'admin'): ?>
    document.getElementById('f_sppg').value = '';
    <?php endif; ?>
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
function editData(id) {
    fetch('/distribusi/get/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modal-title').textContent = 'Edit Distribusi';
            document.getElementById('form-data').action = '/distribusi/update/' + id;
            document.getElementById('f_tanggal').value = data.tanggal_distribusi;
            document.getElementById('f_sekolah').value = data.sekolah_id;
            document.getElementById('f_menu').value = data.menu_id;
            document.getElementById('f_porsi').value = data.jumlah_porsi;
            document.getElementById('f_catatan').value = data.catatan || '';
            document.getElementById('f_status').value = data.status;
            document.getElementById('status-wrap').classList.remove('hidden');
            <?php if (session()->get('role') === 'admin'): ?>
            document.getElementById('f_sppg').value = data.sppg_id;
            <?php endif; ?>
            calcBiaya();
        });
}
document.getElementById('modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });

// AJAX form submit
document.getElementById('form-data').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(res => {
        closeModal();
        if (res.success) {
            showToast(res.message, 'success');
            setTimeout(() => { refreshTable(); }, 100);
        } else if (res.errors) {
            showToast(Object.values(res.errors).join(', '), 'error');
        }
    });
});

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border rounded-xl shadow-lg transition-all duration-300 ' + (type === 'success' ? 'border-green-200' : 'border-red-200');
    toast.innerHTML = '<div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 ' + (type === 'success' ? 'bg-green-100' : 'bg-red-100') + '"><span class="material-symbols-outlined text-lg ' + (type === 'success' ? 'text-green-600' : 'text-red-600') + '">' + (type === 'success' ? 'check_circle' : 'error') + '</span></div><p class="text-sm font-medium text-gray-800">' + msg + '</p>';
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

function refreshTable() {
    fetch('/distribusi/data', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('table-body');
            const isAdmin = <?= session()->get('role') === 'admin' ? 'true' : 'false' ?>;
            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data distribusi.</td></tr>';
                return;
            }
            const sc = {preparing:'bg-blue-100 text-blue-700',in_transit:'bg-orange-100 text-orange-700',delivered:'bg-green-100 text-green-700',cancelled:'bg-red-100 text-red-700'};
            let html = '';
            res.data.forEach(d => {
                const tgl = new Date(d.tanggal_distribusi).toLocaleDateString('id-ID');
                const biaya = Number(d.estimasi_total_biaya).toLocaleString('id-ID');
                const color = sc[d.status] || 'bg-gray-100 text-gray-700';
                const statusText = d.status.replace('_',' ').toUpperCase();
                let actions = '<button onclick="editData(' + d.id + ')" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button>';
                if (isAdmin) actions += '<a href="/distribusi/delete/' + d.id + '" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="event.preventDefault();deleteData(' + d.id + ')"><span class="material-symbols-outlined text-lg">delete</span></a>';
                html += '<tr class="hover:bg-surface-container-lowest transition-colors">';
                html += '<td class="px-5 py-3 text-sm">' + tgl + '</td>';
                html += '<td class="px-5 py-3 text-sm font-medium">' + d.nama_sppg + '</td>';
                html += '<td class="px-5 py-3 text-sm">' + d.nama_sekolah + '</td>';
                html += '<td class="px-5 py-3 text-sm text-on-surface-variant max-w-[150px] truncate">' + d.nama_menu + '</td>';
                html += '<td class="px-5 py-3 text-sm text-right">' + Number(d.jumlah_porsi).toLocaleString('id-ID') + '</td>';
                html += '<td class="px-5 py-3 text-sm text-right">Rp ' + biaya + '</td>';
                html += '<td class="px-5 py-3 text-center"><span class="px-2 py-1 ' + color + ' rounded-full text-[10px] font-bold uppercase">' + statusText + '</span></td>';
                html += '<td class="px-5 py-3 text-right"><div class="flex justify-end gap-1">' + actions + '</div></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;
        });
}

function deleteData(id) {
    if (!confirm('Yakin hapus?')) return;
    fetch('/distribusi/delete/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(() => { showToast('Distribusi berhasil dihapus.', 'success'); refreshTable(); });
}

<?php if (session()->get('role') === 'admin'): ?>
document.getElementById('f_sppg').addEventListener('change', function() {
    const menuSelect = document.getElementById('f_menu');
    menuSelect.innerHTML = '<option value="">Memuat...</option>';
    fetch('/distribusi/getMenuBySppg', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
        body: 'sppg_id=' + this.value + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    }).then(r => r.json()).then(data => {
        menuSelect.innerHTML = '<option value="">Pilih Menu</option>';
        data.forEach(m => {
            const o = document.createElement('option');
            o.value = m.id;
            o.setAttribute('data-harga', m.estimasi_harga_per_porsi);
            o.textContent = m.nama_menu + ' - Rp ' + parseInt(m.estimasi_harga_per_porsi).toLocaleString('id-ID');
            menuSelect.appendChild(o);
        });
    });
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
