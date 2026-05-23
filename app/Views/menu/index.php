<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-6">
    <div>
        <h2 class="text-2xl font-bold text-on-surface">Menu Harian</h2>
        <p class="text-sm text-on-surface-variant">Kelola menu makanan harian untuk distribusi.</p>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg active:scale-95 transition-all">
        <span class="material-symbols-outlined">add</span>
        <span class="text-sm font-medium">Tambah Menu</span>
    </button>
</div>

<div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Foto</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Nama Menu</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">SPPG</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Harga/Porsi</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-outline-variant/50">
                <?php foreach ($menuList as $m): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3">
                        <?php if (!empty($m['foto_menu'])): ?>
                        <img src="/<?= esc($m['foto_menu']) ?>" alt="Foto Menu" class="w-12 h-12 object-cover rounded-lg border border-outline-variant"/>
                        <?php else: ?>
                        <div class="w-12 h-12 bg-surface-container-high rounded-lg flex items-center justify-center"><span class="material-symbols-outlined text-outline">restaurant</span></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3"><p class="font-medium text-sm"><?= esc($m['nama_menu']) ?></p><p class="text-xs text-on-surface-variant"><?= esc($m['deskripsi'] ?? '-') ?></p></td>
                    <td class="px-5 py-3 text-sm"><?= esc($m['nama_sppg']) ?></td>
                    <td class="px-5 py-3 text-sm"><?= date('d/m/Y', strtotime($m['tanggal_menu'])) ?></td>
                    <td class="px-5 py-3 text-sm text-right font-medium">Rp <?= number_format($m['estimasi_harga_per_porsi'], 0, ',', '.') ?></td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="editData(<?= $m['id'] ?>)" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button>
                            <a href="/menu/delete/<?= $m['id'] ?>" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="return confirm('Yakin hapus?')"><span class="material-symbols-outlined text-lg">delete</span></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($menuList)): ?>
                <tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data menu.</td></tr>
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
            <h3 class="text-lg font-semibold" id="modal-title">Tambah Menu</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-data" action="/menu/store" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
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
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Nama Menu</label>
                <input type="text" name="nama_menu" id="f_nama" required class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="Contoh: Nasi Ayam Teriyaki + Susu"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="f_desk" rows="2" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Detail isi menu..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" name="tanggal_menu" id="f_tanggal" required value="<?= date('Y-m-d') ?>" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Harga/Porsi (Rp)</label>
                    <input type="number" name="estimasi_harga_per_porsi" id="f_harga" required min="1" class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none" placeholder="15000"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Foto Menu</label>
                <div class="flex items-center gap-3">
                    <input type="file" name="foto_menu" id="f_foto" accept="image/*" class="w-full border border-outline-variant rounded-lg p-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
                </div>
                <div id="foto-preview" class="mt-2 hidden">
                    <img id="foto-preview-img" src="" alt="Preview" class="h-20 w-20 object-cover rounded-lg border border-outline-variant"/>
                </div>
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
    document.getElementById('modal-title').textContent = 'Tambah Menu';
    document.getElementById('form-data').action = '/menu/store';
    document.getElementById('f_nama').value = '';
    document.getElementById('f_desk').value = '';
    document.getElementById('f_tanggal').value = '<?= date('Y-m-d') ?>';
    document.getElementById('f_harga').value = '';
    document.getElementById('f_foto').value = '';
    document.getElementById('foto-preview').classList.add('hidden');
    <?php if (session()->get('role') === 'admin'): ?>
    document.getElementById('f_sppg').value = '';
    <?php endif; ?>
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
function editData(id) {
    fetch('/menu/get/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modal-title').textContent = 'Edit Menu';
            document.getElementById('form-data').action = '/menu/update/' + id;
            document.getElementById('f_nama').value = data.nama_menu;
            document.getElementById('f_desk').value = data.deskripsi || '';
            document.getElementById('f_tanggal').value = data.tanggal_menu;
            document.getElementById('f_harga').value = data.estimasi_harga_per_porsi;
            document.getElementById('f_foto').value = '';
            if (data.foto_menu) {
                document.getElementById('foto-preview').classList.remove('hidden');
                document.getElementById('foto-preview-img').src = '/' + data.foto_menu;
            } else {
                document.getElementById('foto-preview').classList.add('hidden');
            }
            <?php if (session()->get('role') === 'admin'): ?>
            document.getElementById('f_sppg').value = data.sppg_id;
            <?php endif; ?>
        });
}
document.getElementById('modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });

// Image preview on file select
document.getElementById('f_foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('foto-preview').classList.remove('hidden');
            document.getElementById('foto-preview-img').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
});

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
    fetch('/menu/data', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('table-body');
            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data menu.</td></tr>';
                return;
            }
            let html = '';
            res.data.forEach(m => {
                const tgl = new Date(m.tanggal_menu).toLocaleDateString('id-ID');
                const harga = Number(m.estimasi_harga_per_porsi).toLocaleString('id-ID');
                const foto = m.foto_menu
                    ? '<img src="/' + m.foto_menu + '" alt="Foto" class="w-12 h-12 object-cover rounded-lg border border-outline-variant"/>'
                    : '<div class="w-12 h-12 bg-surface-container-high rounded-lg flex items-center justify-center"><span class="material-symbols-outlined text-outline">restaurant</span></div>';
                html += '<tr class="hover:bg-surface-container-lowest transition-colors">';
                html += '<td class="px-5 py-3">' + foto + '</td>';
                html += '<td class="px-5 py-3"><p class="font-medium text-sm">' + m.nama_menu + '</p><p class="text-xs text-on-surface-variant">' + (m.deskripsi || '-') + '</p></td>';
                html += '<td class="px-5 py-3 text-sm">' + (m.nama_sppg || '-') + '</td>';
                html += '<td class="px-5 py-3 text-sm">' + tgl + '</td>';
                html += '<td class="px-5 py-3 text-sm text-right font-medium">Rp ' + harga + '</td>';
                html += '<td class="px-5 py-3 text-right"><div class="flex justify-end gap-1"><button onclick="editData(' + m.id + ')" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg"><span class="material-symbols-outlined text-lg">edit</span></button><a href="/menu/delete/' + m.id + '" class="p-1.5 hover:bg-error/10 text-error rounded-lg" onclick="event.preventDefault();deleteData(' + m.id + ')"><span class="material-symbols-outlined text-lg">delete</span></a></div></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;
        });
}

function deleteData(id) {
    if (!confirm('Yakin hapus?')) return;
    fetch('/menu/delete/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(() => { showToast('Menu berhasil dihapus.', 'success'); refreshTable(); });
}
</script>
<?= $this->endSection() ?>
