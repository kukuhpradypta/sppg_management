<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-6">
    <div>
        <nav class="flex text-xs text-outline mb-1 gap-2"><span>Manajemen</span><span>/</span><span class="text-primary font-medium">Data User</span></nav>
        <h2 class="text-2xl font-bold text-on-surface">Manajemen User</h2>
        <p class="text-sm text-on-surface-variant">Kelola akun pengguna sistem SPPG Management.</p>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:shadow-lg active:scale-95 transition-all">
        <span class="material-symbols-outlined">person_add</span>
        <span class="text-sm font-medium">Tambah User</span>
    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center text-primary"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Total User</p><p class="text-xl font-bold" id="stat-total"><?= number_format($totalUser) ?></p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-secondary/5 rounded-lg flex items-center justify-center text-secondary"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Admin</p><p class="text-xl font-bold" id="stat-admin"><?= number_format($totalAdmin) ?></p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-700"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">store</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">SPPG</p><p class="text-xl font-bold" id="stat-sppg"><?= number_format($totalSppg) ?></p></div>
    </div>
    <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-700"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified_user</span></div>
        <div><p class="text-[10px] text-outline font-bold uppercase">Aktif</p><p class="text-xl font-bold" id="stat-aktif"><?= number_format($totalAktif) ?></p></div>
    </div>
</div>

<!-- Filter -->
<div class="bg-surface-container-low p-4 rounded-t-xl border border-outline-variant border-b-0 flex flex-wrap gap-3 items-center">
    <div class="flex gap-3 items-center">
        <div class="flex items-center gap-2 bg-white border border-outline-variant px-3 py-1 rounded-md">
            <span class="text-xs font-bold text-outline uppercase">Role</span>
            <select id="filter-role" class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer" onchange="refreshTable()">
                <option value="Semua">Semua</option>
                <option value="admin">Admin</option>
                <option value="sppg">SPPG</option>
            </select>
        </div>
        <div class="flex items-center gap-2 bg-white border border-outline-variant px-3 py-1 rounded-md">
            <span class="text-xs font-bold text-outline uppercase">Status</span>
            <select id="filter-status" class="border-none bg-transparent text-sm font-medium focus:ring-0 cursor-pointer" onchange="refreshTable()">
                <option value="Semua">Semua</option>
                <option value="Aktif">Aktif</option>
                <option value="Non-Aktif">Non-Aktif</option>
            </select>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white border border-outline-variant rounded-b-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Nama Lengkap</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Username</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Email</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">Role</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase">SPPG</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-bold text-primary uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-outline-variant/50">
                <?php foreach ($userList as $u): ?>
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                            </div>
                            <span class="font-medium text-sm"><?= esc($u['nama_lengkap']) ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm font-mono text-on-surface-variant"><?= esc($u['username']) ?></td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant"><?= esc($u['email']) ?></td>
                    <td class="px-5 py-3">
                        <?php if ($u['role'] === 'admin'): ?>
                            <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-1 rounded-full">ADMIN</span>
                        <?php else: ?>
                            <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-full">SPPG</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-sm text-on-surface-variant"><?= esc($u['nama_sppg'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-center">
                        <?= $u['is_active']
                            ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>'
                            : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>' ?>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="editData(<?= $u['id'] ?>)" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg" title="Edit">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <?php if ($u['id'] != session()->get('user_id')): ?>
                            <a href="/users/delete/<?= $u['id'] ?>" class="p-1.5 hover:bg-error/10 text-error rounded-lg" title="Hapus" onclick="return confirm('Yakin hapus user ini?')">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($userList)): ?>
                <tr><td colspan="7" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data user.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-outline-variant"><?= $pager->links() ?></div>
</div>

<!-- Modal Tambah/Edit User -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 bg-primary text-on-primary flex items-center justify-between">
            <h3 class="text-lg font-semibold" id="modal-title">Tambah User Baru</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-full">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-data" action="/users/store" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="f_nama" required
                        class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Nama lengkap user..."/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="username" id="f_username" required
                        class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="username..."/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" id="f_email" required
                        class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="email@domain.com"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">
                        Password <span id="password-hint" class="text-outline/60 font-normal normal-case">(min. 6 karakter)</span>
                    </label>
                    <input type="password" name="password" id="f_password"
                        class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="••••••••"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Role</label>
                    <select name="role" id="f_role" required onchange="toggleSppgField()"
                        class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="sppg">SPPG</option>
                    </select>
                </div>
            </div>

            <!-- Field SPPG (tampil hanya jika role = sppg) -->
            <div id="sppg-field" class="hidden">
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1">Pilih SPPG</label>
                <select name="sppg_id" id="f_sppg"
                    class="w-full border border-outline-variant rounded-lg p-3 focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Tidak Ada --</option>
                    <?php foreach ($sppgList as $sppg): ?>
                    <option value="<?= $sppg['id'] ?>"><?= esc($sppg['nama_sppg']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg">
                <div>
                    <p class="font-medium text-sm">Status Aktif</p>
                    <p class="text-xs text-on-surface-variant">User dapat login ke sistem</p>
                </div>
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
// Data SPPG untuk digunakan saat edit
const sppgData = <?= json_encode(array_map(fn($s) => ['id' => $s['id'], 'nama_sppg' => $s['nama_sppg']], $sppgList)) ?>;

function toggleSppgField() {
    const role = document.getElementById('f_role').value;
    const sppgField = document.getElementById('sppg-field');
    if (role === 'sppg') {
        sppgField.classList.remove('hidden');
    } else {
        sppgField.classList.add('hidden');
        document.getElementById('f_sppg').value = '';
    }
}

function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
    document.getElementById('modal-title').textContent = 'Tambah User Baru';
    document.getElementById('form-data').action = '/users/store';
    document.getElementById('f_nama').value = '';
    document.getElementById('f_username').value = '';
    document.getElementById('f_email').value = '';
    document.getElementById('f_password').value = '';
    document.getElementById('f_password').required = true;
    document.getElementById('f_role').value = '';
    document.getElementById('f_sppg').value = '';
    document.getElementById('f_active').checked = true;
    document.getElementById('password-hint').textContent = '(min. 6 karakter)';
    document.getElementById('sppg-field').classList.add('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}

function editData(id) {
    fetch('/users/get/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
            document.getElementById('modal-title').textContent = 'Edit User';
            document.getElementById('form-data').action = '/users/update/' + id;
            document.getElementById('f_nama').value = data.nama_lengkap;
            document.getElementById('f_username').value = data.username;
            document.getElementById('f_email').value = data.email;
            document.getElementById('f_password').value = '';
            document.getElementById('f_password').required = false;
            document.getElementById('f_role').value = data.role;
            document.getElementById('f_active').checked = (data.is_active == 1);
            document.getElementById('password-hint').textContent = '(kosongkan jika tidak diubah)';

            // Toggle SPPG field
            if (data.role === 'sppg') {
                document.getElementById('sppg-field').classList.remove('hidden');
                document.getElementById('f_sppg').value = data.sppg_id || '';
            } else {
                document.getElementById('sppg-field').classList.add('hidden');
                document.getElementById('f_sppg').value = '';
            }
        });
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

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
        if (res.success) {
            closeModal();
            showToast(res.message, 'success');
            refreshTable();
        } else if (res.errors) {
            showToast(Object.values(res.errors).join(' | '), 'error');
        }
    });
});

function refreshTable() {
    const role   = document.getElementById('filter-role').value;
    const status = document.getElementById('filter-status').value;
    const params = new URLSearchParams();
    if (role   !== 'Semua') params.set('role', role);
    if (status !== 'Semua') params.set('status', status);

    fetch('/users/data?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const currentUserId = <?= session()->get('user_id') ?? 'null' ?>;
            const tbody = document.getElementById('table-body');
            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-5 py-8 text-center text-on-surface-variant">Tidak ada data user.</td></tr>';
            } else {
                tbody.innerHTML = res.data.map(u => {
                    const roleBadge = u.role === 'admin'
                        ? '<span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-1 rounded-full">ADMIN</span>'
                        : '<span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-full">SPPG</span>';
                    const statusBadge = u.is_active == 1
                        ? '<span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">AKTIF</span>'
                        : '<span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">NON-AKTIF</span>';
                    const deleteBtn = u.id != currentUserId
                        ? `<a href="/users/delete/${u.id}" class="p-1.5 hover:bg-error/10 text-error rounded-lg" title="Hapus" onclick="return confirm('Yakin hapus user ini?')"><span class="material-symbols-outlined text-lg">delete</span></a>`
                        : '';
                    return `
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">${u.nama_lengkap.charAt(0).toUpperCase()}</div>
                                    <span class="font-medium text-sm">${u.nama_lengkap}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm font-mono text-on-surface-variant">${u.username}</td>
                            <td class="px-5 py-3 text-sm text-on-surface-variant">${u.email}</td>
                            <td class="px-5 py-3">${roleBadge}</td>
                            <td class="px-5 py-3 text-sm text-on-surface-variant">${u.nama_sppg || '-'}</td>
                            <td class="px-5 py-3 text-center">${statusBadge}</td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <button onclick="editData(${u.id})" class="p-1.5 hover:bg-primary/10 text-primary rounded-lg" title="Edit"><span class="material-symbols-outlined text-lg">edit</span></button>
                                    ${deleteBtn}
                                </div>
                            </td>
                        </tr>`;
                }).join('');
            }
            // Update stats
            document.getElementById('stat-total').textContent = Number(res.stats.totalUser).toLocaleString('id-ID');
            document.getElementById('stat-admin').textContent = Number(res.stats.totalAdmin).toLocaleString('id-ID');
            document.getElementById('stat-sppg').textContent  = Number(res.stats.totalSppg).toLocaleString('id-ID');
            document.getElementById('stat-aktif').textContent = Number(res.stats.totalAktif).toLocaleString('id-ID');
        });
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 bg-white border rounded-xl shadow-lg transition-all duration-300 ' + (type === 'success' ? 'border-green-200' : 'border-red-200');
    toast.innerHTML = `<div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 ${type === 'success' ? 'bg-green-100' : 'bg-red-100'}"><span class="material-symbols-outlined text-lg ${type === 'success' ? 'text-green-600' : 'text-red-600'}">${type === 'success' ? 'check_circle' : 'error'}</span></div><p class="text-sm font-medium text-gray-800">${msg}</p>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3500);
}
</script>
<?= $this->endSection() ?>
