<?php

namespace App\Controllers;

use App\Models\MenuHarianModel;
use App\Models\SppgModel;

class Menu extends BaseController
{
    protected $menuModel;
    protected $sppgModel;

    public function __construct()
    {
        $this->menuModel = new MenuHarianModel();
        $this->sppgModel = new SppgModel();
    }

    public function index()
    {
        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');

        $builder = $this->menuModel->getWithSppg($role === 'sppg' ? $sppgId : null);

        $data = [
            'title'    => 'Menu Harian',
            'menuList' => $builder->paginate(10),
            'pager'    => $this->menuModel->pager,
            'sppgList' => $role === 'admin' ? $this->sppgModel->getActiveSppg() : [],
            'sppgId'   => $sppgId,
        ];
        return view('menu/index', $data);
    }

    public function getById($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        if ($role === 'sppg' && $menu['sppg_id'] != $sppgId) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        return $this->response->setJSON($menu);
    }

    public function getData()
    {
        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        $list = $this->menuModel->getWithSppg($role === 'sppg' ? $sppgId : null)->findAll();
        return $this->response->setJSON(['data' => $list]);
    }

    public function store()
    {
        $rules = [
            'nama_menu'                => 'required|max_length[200]',
            'tanggal_menu'             => 'required|valid_date',
            'estimasi_harga_per_porsi' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        $assignedSppgId = $role === 'admin' ? $this->request->getPost('sppg_id') : $sppgId;

        // Handle file upload
        $fotoMenu = null;
        $file = $this->request->getFile('foto_menu');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $sppg = $this->sppgModel->find($assignedSppgId);
            $folderName = str_replace(' ', '_', strtolower($sppg['nama_sppg'] ?? 'sppg'));
            $tanggal = $this->request->getPost('tanggal_menu');
            $fileName = 'menu_' . $tanggal . '_' . time() . '.' . $file->getExtension();

            $uploadPath = FCPATH . 'uploads/menu/' . $folderName;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $fileName);
            $fotoMenu = 'uploads/menu/' . $folderName . '/' . $fileName;
        }

        $data = [
            'sppg_id'                  => $assignedSppgId,
            'nama_menu'                => $this->request->getPost('nama_menu'),
            'deskripsi'                => $this->request->getPost('deskripsi'),
            'tanggal_menu'             => $this->request->getPost('tanggal_menu'),
            'estimasi_harga_per_porsi' => $this->request->getPost('estimasi_harga_per_porsi'),
        ];
        if ($fotoMenu) {
            $data['foto_menu'] = $fotoMenu;
        }

        $this->menuModel->save($data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Menu berhasil ditambahkan.']);
        }
        return redirect()->to('/menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Data tidak ditemukan.');
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        if ($role === 'sppg' && $menu['sppg_id'] != $sppgId) {
            return redirect()->to('/menu')->with('error', 'Anda tidak memiliki akses.');
        }

        $rules = [
            'nama_menu'                => 'required|max_length[200]',
            'tanggal_menu'             => 'required|valid_date',
            'estimasi_harga_per_porsi' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $assignedSppgId = $role === 'admin' ? $this->request->getPost('sppg_id') : $sppgId;

        // Handle file upload
        $fotoMenu = null;
        $file = $this->request->getFile('foto_menu');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $sppg = $this->sppgModel->find($assignedSppgId);
            $folderName = str_replace(' ', '_', strtolower($sppg['nama_sppg'] ?? 'sppg'));
            $tanggal = $this->request->getPost('tanggal_menu');
            $fileName = 'menu_' . $tanggal . '_' . time() . '.' . $file->getExtension();

            $uploadPath = FCPATH . 'uploads/menu/' . $folderName;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            // Delete old file if exists
            if (!empty($menu['foto_menu']) && file_exists(FCPATH . $menu['foto_menu'])) {
                unlink(FCPATH . $menu['foto_menu']);
            }
            $file->move($uploadPath, $fileName);
            $fotoMenu = 'uploads/menu/' . $folderName . '/' . $fileName;
        }

        $data = [
            'sppg_id'                  => $assignedSppgId,
            'nama_menu'                => $this->request->getPost('nama_menu'),
            'deskripsi'                => $this->request->getPost('deskripsi'),
            'tanggal_menu'             => $this->request->getPost('tanggal_menu'),
            'estimasi_harga_per_porsi' => $this->request->getPost('estimasi_harga_per_porsi'),
        ];
        if ($fotoMenu) {
            $data['foto_menu'] = $fotoMenu;
        }

        $this->menuModel->update($id, $data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Menu berhasil diperbarui.']);
        }
        return redirect()->to('/menu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function delete($id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Data tidak ditemukan.');
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        if ($role === 'sppg' && $menu['sppg_id'] != $sppgId) {
            return redirect()->to('/menu')->with('error', 'Anda tidak memiliki akses.');
        }

        $this->menuModel->delete($id);
        return redirect()->to('/menu')->with('success', 'Menu berhasil dihapus.');
    }
}
