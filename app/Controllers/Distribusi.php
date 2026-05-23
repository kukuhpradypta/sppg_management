<?php

namespace App\Controllers;

use App\Models\DistribusiModel;
use App\Models\MenuHarianModel;
use App\Models\SekolahModel;
use App\Models\SppgModel;

class Distribusi extends BaseController
{
    protected $distribusiModel;
    protected $menuModel;
    protected $sekolahModel;
    protected $sppgModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiModel();
        $this->menuModel       = new MenuHarianModel();
        $this->sekolahModel    = new SekolahModel();
        $this->sppgModel       = new SppgModel();
    }

    public function index()
    {
        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');

        $builder = $this->distribusiModel->getWithRelations($role === 'sppg' ? $sppgId : null);

        $data = [
            'title'          => 'Data Distribusi',
            'distribusiList' => $builder->paginate(10),
            'pager'          => $this->distribusiModel->pager,
            'sekolahList'    => $this->sekolahModel->getActiveSchools(),
            'sppgList'       => $role === 'admin' ? $this->sppgModel->getActiveSppg() : [],
            'menuList'       => $role === 'sppg' ? $this->menuModel->where('sppg_id', $sppgId)->findAll() : [],
            'sppgId'         => $sppgId,
        ];
        return view('distribusi/index', $data);
    }

    public function getById($id)
    {
        $distribusi = $this->distribusiModel->find($id);
        if (!$distribusi) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        if ($role === 'sppg' && $distribusi['sppg_id'] != $sppgId) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        return $this->response->setJSON($distribusi);
    }

    public function getData()
    {
        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        $list = $this->distribusiModel->getWithRelations($role === 'sppg' ? $sppgId : null)->findAll();
        return $this->response->setJSON(['data' => $list]);
    }

    public function store()
    {
        $rules = [
            'tanggal_distribusi' => 'required|valid_date',
            'sekolah_id'         => 'required|integer',
            'menu_id'            => 'required|integer',
            'jumlah_porsi'       => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        $menuId = $this->request->getPost('menu_id');

        $menu = $this->menuModel->find($menuId);
        $jumlahPorsi = (int) $this->request->getPost('jumlah_porsi');
        $totalBiaya  = $jumlahPorsi * ($menu['estimasi_harga_per_porsi'] ?? 0);

        $this->distribusiModel->save([
            'tanggal_distribusi'   => $this->request->getPost('tanggal_distribusi'),
            'sppg_id'              => $role === 'admin' ? $this->request->getPost('sppg_id') : $sppgId,
            'sekolah_id'           => $this->request->getPost('sekolah_id'),
            'menu_id'              => $menuId,
            'jumlah_porsi'         => $jumlahPorsi,
            'estimasi_total_biaya' => $totalBiaya,
            'catatan'              => $this->request->getPost('catatan'),
            'status'               => 'preparing',
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Distribusi berhasil dicatat.']);
        }
        return redirect()->to('/distribusi')->with('success', 'Distribusi berhasil dicatat.');
    }

    public function update($id)
    {
        $distribusi = $this->distribusiModel->find($id);
        if (!$distribusi) {
            return redirect()->to('/distribusi')->with('error', 'Data tidak ditemukan.');
        }

        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');
        if ($role === 'sppg' && $distribusi['sppg_id'] != $sppgId) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses.');
        }

        $rules = [
            'tanggal_distribusi' => 'required|valid_date',
            'sekolah_id'         => 'required|integer',
            'menu_id'            => 'required|integer',
            'jumlah_porsi'       => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $menuId = $this->request->getPost('menu_id');
        $menu = $this->menuModel->find($menuId);
        $jumlahPorsi = (int) $this->request->getPost('jumlah_porsi');
        $totalBiaya  = $jumlahPorsi * ($menu['estimasi_harga_per_porsi'] ?? 0);

        $updateData = [
            'tanggal_distribusi'   => $this->request->getPost('tanggal_distribusi'),
            'sekolah_id'           => $this->request->getPost('sekolah_id'),
            'menu_id'              => $menuId,
            'jumlah_porsi'         => $jumlahPorsi,
            'estimasi_total_biaya' => $totalBiaya,
            'catatan'              => $this->request->getPost('catatan'),
            'status'               => $this->request->getPost('status') ?? $distribusi['status'],
        ];

        if ($role === 'admin') {
            $updateData['sppg_id'] = $this->request->getPost('sppg_id');
        }

        $this->distribusiModel->update($id, $updateData);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Distribusi berhasil diperbarui.']);
        }
        return redirect()->to('/distribusi')->with('success', 'Distribusi berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses.');
        }

        $this->distribusiModel->delete($id);
        return redirect()->to('/distribusi')->with('success', 'Distribusi berhasil dihapus.');
    }

    public function getMenuBySppg()
    {
        $sppgId = $this->request->getPost('sppg_id');
        $menus  = $this->menuModel->where('sppg_id', $sppgId)->findAll();
        return $this->response->setJSON($menus);
    }
}
