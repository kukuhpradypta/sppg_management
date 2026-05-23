<?php

namespace App\Controllers;

use App\Models\SekolahModel;

class Sekolah extends BaseController
{
    protected $sekolahModel;

    public function __construct()
    {
        $this->sekolahModel = new SekolahModel();
    }

    public function index()
    {
        $jenjang = $this->request->getGet('jenjang');
        $status  = $this->request->getGet('status');

        $builder = $this->sekolahModel;

        if ($jenjang && $jenjang !== 'Semua') {
            $builder = $builder->where('jenjang', $jenjang);
        }
        if ($status && $status !== 'Semua') {
            $isActive = ($status === 'Aktif') ? 1 : 0;
            $builder = $builder->where('is_active', $isActive);
        }

        $data = [
            'title'        => 'Data Sekolah',
            'sekolahList'  => $builder->paginate(10),
            'pager'        => $this->sekolahModel->pager,
            'totalSekolah' => $this->sekolahModel->countAllResults(false),
            'totalSiswa'   => $this->sekolahModel->getTotalSiswa(),
            'totalAktif'   => $this->sekolahModel->where('is_active', 1)->countAllResults(false),
            'totalAll'     => $this->sekolahModel->countAllResults(false),
        ];

        return view('sekolah/index', $data);
    }

    // AJAX: Get detail by ID
    public function getById($id)
    {
        $sekolah = $this->sekolahModel->find($id);
        if (!$sekolah) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }
        return $this->response->setJSON($sekolah);
    }

    // AJAX: Get all data for table refresh
    public function getData()
    {
        $jenjang = $this->request->getGet('jenjang');
        $status  = $this->request->getGet('status');

        $builder = $this->sekolahModel;
        if ($jenjang && $jenjang !== 'Semua') {
            $builder = $builder->where('jenjang', $jenjang);
        }
        if ($status && $status !== 'Semua') {
            $isActive = ($status === 'Aktif') ? 1 : 0;
            $builder = $builder->where('is_active', $isActive);
        }

        $list = $builder->findAll();
        $stats = [
            'totalSekolah' => $this->sekolahModel->countAllResults(false),
            'totalSiswa'   => $this->sekolahModel->getTotalSiswa(),
            'totalAktif'   => $this->sekolahModel->where('is_active', 1)->countAllResults(false),
            'totalAll'     => $this->sekolahModel->countAllResults(false),
        ];

        return $this->response->setJSON(['data' => $list, 'stats' => $stats]);
    }

    public function store()
    {
        $rules = [
            'nama_sekolah' => 'required|min_length[3]|max_length[150]',
            'alamat'       => 'required',
            'jenjang'      => 'required|in_list[TK,SD,SMP,SMA]',
            'jumlah_siswa' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sekolahModel->save([
            'nama_sekolah' => $this->request->getPost('nama_sekolah'),
            'alamat'       => $this->request->getPost('alamat'),
            'jenjang'      => $this->request->getPost('jenjang'),
            'jumlah_siswa' => $this->request->getPost('jumlah_siswa'),
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data sekolah berhasil ditambahkan.']);
        }
        return redirect()->to('/sekolah')->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama_sekolah' => 'required|min_length[3]|max_length[150]',
            'alamat'       => 'required',
            'jenjang'      => 'required|in_list[TK,SD,SMP,SMA]',
            'jumlah_siswa' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sekolahModel->update($id, [
            'nama_sekolah' => $this->request->getPost('nama_sekolah'),
            'alamat'       => $this->request->getPost('alamat'),
            'jenjang'      => $this->request->getPost('jenjang'),
            'jumlah_siswa' => $this->request->getPost('jumlah_siswa'),
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data sekolah berhasil diperbarui.']);
        }
        return redirect()->to('/sekolah')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->sekolahModel->delete($id);
        return redirect()->to('/sekolah')->with('success', 'Data sekolah berhasil dihapus.');
    }
}
