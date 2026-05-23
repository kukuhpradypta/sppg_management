<?php

namespace App\Controllers;

use App\Models\SppgModel;

class Sppg extends BaseController
{
    protected $sppgModel;

    public function __construct()
    {
        $this->sppgModel = new SppgModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Data SPPG',
            'sppgList' => $this->sppgModel->paginate(10),
            'pager'    => $this->sppgModel->pager,
        ];
        return view('sppg/index', $data);
    }

    public function getById($id)
    {
        $sppg = $this->sppgModel->find($id);
        if (!$sppg) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }
        return $this->response->setJSON($sppg);
    }

    public function getData()
    {
        $list = $this->sppgModel->findAll();
        return $this->response->setJSON(['data' => $list]);
    }

    public function store()
    {
        $rules = [
            'nama_sppg'        => 'required|min_length[3]|max_length[150]',
            'alamat'           => 'required',
            'penanggung_jawab' => 'required|max_length[100]',
            'nomor_telepon'    => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sppgModel->save([
            'nama_sppg'        => $this->request->getPost('nama_sppg'),
            'alamat'           => $this->request->getPost('alamat'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'nomor_telepon'    => $this->request->getPost('nomor_telepon'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data SPPG berhasil ditambahkan.']);
        }
        return redirect()->to('/sppg')->with('success', 'Data SPPG berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama_sppg'        => 'required|min_length[3]|max_length[150]',
            'alamat'           => 'required',
            'penanggung_jawab' => 'required|max_length[100]',
            'nomor_telepon'    => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sppgModel->update($id, [
            'nama_sppg'        => $this->request->getPost('nama_sppg'),
            'alamat'           => $this->request->getPost('alamat'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'nomor_telepon'    => $this->request->getPost('nomor_telepon'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data SPPG berhasil diperbarui.']);
        }
        return redirect()->to('/sppg')->with('success', 'Data SPPG berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->sppgModel->delete($id);
        return redirect()->to('/sppg')->with('success', 'Data SPPG berhasil dihapus.');
    }
}
