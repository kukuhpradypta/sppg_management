<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SppgModel;

class Users extends BaseController
{
    protected $userModel;
    protected $sppgModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->sppgModel = new SppgModel();
    }

    public function index()
    {
        $role   = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        // Hitung stats pakai query builder langsung (bukan model instance)
        // supaya tidak terpengaruh state JOIN dari query utama
        $db = \Config\Database::connect();
        $stats = [
            'totalUser'  => $db->table('users')->where('deleted_at IS NULL', null, false)->countAllResults(),
            'totalAdmin' => $db->table('users')->where('deleted_at IS NULL', null, false)->where('role', 'admin')->countAllResults(),
            'totalSppg'  => $db->table('users')->where('deleted_at IS NULL', null, false)->where('role', 'sppg')->countAllResults(),
            'totalAktif' => $db->table('users')->where('deleted_at IS NULL', null, false)->where('is_active', 1)->countAllResults(),
        ];

        $builder = $this->userModel
            ->select('users.*, sppg.nama_sppg')
            ->join('sppg', 'sppg.id = users.sppg_id', 'left');

        if ($role && $role !== 'Semua') {
            $builder = $builder->where('users.role', $role);
        }
        if ($status && $status !== 'Semua') {
            $isActive = ($status === 'Aktif') ? 1 : 0;
            $builder  = $builder->where('users.is_active', $isActive);
        }

        $data = [
            'title'      => 'Manajemen User',
            'userList'   => $builder->paginate(10),
            'pager'      => $this->userModel->pager,
            'totalUser'  => $stats['totalUser'],
            'totalAdmin' => $stats['totalAdmin'],
            'totalSppg'  => $stats['totalSppg'],
            'totalAktif' => $stats['totalAktif'],
            'sppgList'   => $this->sppgModel->getActiveSppg(),
        ];

        return view('users/index', $data);
    }

    public function getById($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }
        // Jangan kirim password hash ke client
        unset($user['password']);
        return $this->response->setJSON($user);
    }

    public function getData()
    {
        $role   = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        $builder = $this->userModel
            ->select('users.*, sppg.nama_sppg')
            ->join('sppg', 'sppg.id = users.sppg_id', 'left');

        if ($role && $role !== 'Semua') {
            $builder = $builder->where('users.role', $role);
        }
        if ($status && $status !== 'Semua') {
            $isActive = ($status === 'Aktif') ? 1 : 0;
            $builder  = $builder->where('users.is_active', $isActive);
        }

        $list = $builder->findAll();

        // Hapus password dari tiap row
        foreach ($list as &$row) {
            unset($row['password']);
        }

        $db = \Config\Database::connect();
        $stats = [
            'totalUser'  => $db->table('users')->where('deleted_at IS NULL', null, false)->countAllResults(),
            'totalAdmin' => $db->table('users')->where('deleted_at IS NULL', null, false)->where('role', 'admin')->countAllResults(),
            'totalSppg'  => $db->table('users')->where('deleted_at IS NULL', null, false)->where('role', 'sppg')->countAllResults(),
            'totalAktif' => $db->table('users')->where('deleted_at IS NULL', null, false)->where('is_active', 1)->countAllResults(),
        ];

        return $this->response->setJSON(['data' => $list, 'stats' => $stats]);
    }

    public function store()
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'username'     => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'        => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,sppg]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role   = $this->request->getPost('role');
        $sppgId = ($role === 'sppg') ? $this->request->getPost('sppg_id') : null;

        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $role,
            'sppg_id'      => $sppgId ?: null,
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'User berhasil ditambahkan.']);
        }
        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[100]',
            'username'     => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email'        => "required|valid_email|max_length[100]|is_unique[users.email,id,{$id}]",
            'role'         => 'required|in_list[admin,sppg]',
        ];

        // Password opsional saat update
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role   = $this->request->getPost('role');
        $sppgId = ($role === 'sppg') ? $this->request->getPost('sppg_id') : null;

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'role'         => $role,
            'sppg_id'      => $sppgId ?: null,
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'User berhasil diperbarui.']);
        }
        return redirect()->to('/users')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Jangan hapus diri sendiri
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User berhasil dihapus.');
    }
}
