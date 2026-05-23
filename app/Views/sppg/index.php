<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-6">
    <div>
        <nav class="flex text-xs text-outline mb-1 gap-2"><span>Manajemen</span><span>/</span><span class="text-primary font-medium">Data SPPG</span></nav>
        <h2 class="text-2xl font-bold text-on-surface">Data SPPG</h2>
        <p class="text-sm text-on-surface-variant">Kelola data Satuan Pelayanan Pemenuhan Gizi.</p>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg active:scale-95 transition-all">
        <span class="material-symbols-outlined">add</span>
        <span class="text-sm font-medium">Tambah SPPG</span>
    </button>
</div>

<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Nama SPPG</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Alamat</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Penanggung Jawab</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Telepon</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-outline-variant/50">
                <?php foreach ($sppgList as $s): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3 font-medium text-sm"><?= esc($s['nama_sppg']) ?></td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant max-w-xs truncate"><?= esc($s['alamat']) ?></td>
                    <td class="px-5 py-3 text-sm"><?= esc($s['penanggung_jawab']) ?></td>
                    <td class="px-5 py-3 text-sm"><?= esc($s['nomor_telepon']) ?></td>
                    <td class="px-5 py-3 text-center"><?= $s['is_active'] ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>' : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>' ?></td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="editData(<?= $s['id'] ?>)" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button>
                            <button onclick="deleteData(<?= $s['id'] ?>)" class="p-1.5 hover:bg-error/10 text-error rounded-lg"><span class="material-symbols-outlined text-lg">delete</span></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($sppgList)): ?>
                <tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data SPPG.</td></tr>
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
            <h3 class="text-lg font-semibold" id="modal-title">Tambah SPPG Baru</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-data" action="/sppg/store" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Nama SPPG</label>
                <input type="text" name="nama_sppg" id="f_nama" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="Nama SPPG..."/>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Alamat</label>
                <textarea name="alamat" id="f_alamat" rows="2" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Alamat lengkap..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" id="f_pj" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="Nama PJ..."/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" id="f_telp" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="08xxx"/>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg">
                <div><p class="font-medium text-sm">Status Aktif</p></div>
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
    document.getElementById('modal-title').textContent = 'Tambah SPPG Baru';
    document.getElementById('form-data').action = '/sppg/store';
    document.getElementById('f_nama').value = '';
    document.getElementById('f_alamat').value = '';
    document.getElementById('f_pj').value = '';
    document.getElementById('f_telp').value = '';
    document.getElementById('f_active').checked = true;
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
function editData(id) {
    fetch('/sppg/get/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modal-title').textContent = 'Edit SPPG';
            document.getElementById('form-data').action = '/sppg/update/' + id;
            document.getElementById('f_nama').value = data.nama_sppg;
            document.getElementById('f_alamat').value = data.alamat;
            document.getElementById('f_pj').value = data.penanggung_jawab;
            document.getElementById('f_telp').value = data.nomor_telepon;
            document.getElementById('f_active').checked = (data.is_active == 1);
        });
}
document.getElementById('modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });

// AJAX form submit
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

function deleteData(id) {
    if (!confirm('Yakin hapus data ini?')) return;
    fetch('/sppg/delete/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(() => { showToast('Data berhasil dihapus.', 'success'); refreshTable(); });
}

function refreshTable() {
    fetch('/sppg/data', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('table-body');
            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data.</td></tr>';
                return;
            }
            let html = '';
            res.data.forEach(s => {
                const statusBadge = s.is_active == 1
                    ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>'
                    : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>';
                html += '<tr class="hover:bg-surface-container-lowest transition-colors">';
                html += '<td class="px-5 py-3 font-medium text-sm">' + s.nama_sppg + '</td>';
                html += '<td class="px-5 py-3 text-sm text-on-surface-variant max-w-xs truncate">' + s.alamat + '</td>';
                html += '<td class="px-5 py-3 text-sm">' + s.penanggung_jawab + '</td>';
                html += '<td class="px-5 py-3 text-sm">' + s.nomor_telepon + '</td>';
                html += '<td class="px-5 py-3 text-center">' + statusBadge + '</td>';
                html += '<td class="px-5 py-3 text-right"><div class="flex justify-end gap-1"><button onclick="editData(' + s.id + ')" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button><button onclick="deleteData(' + s.id + ')" class="p-1.5 hover:bg-error/10 text-error rounded-lg"><span class="material-symbols-outlined text-lg">delete</span></button></div></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;
        });
}
</script>
<?= $this->endSection() ?>
