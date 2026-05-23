<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'nama_sekolah', 'alamat', 'jenjang',
        'jumlah_siswa', 'is_active',
    ];

    protected $validationRules = [
        'nama_sekolah' => 'required|min_length[3]|max_length[150]',
        'alamat'       => 'required',
        'jenjang'      => 'required|in_list[TK,SD,SMP,SMA]',
        'jumlah_siswa' => 'required|integer|greater_than_equal_to[0]',
    ];

    public function getActiveSchools()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function countByJenjang()
    {
        return $this->select('jenjang, COUNT(*) as total')
                    ->where('is_active', 1)
                    ->groupBy('jenjang')
                    ->findAll();
    }

    public function getTotalSiswa()
    {
        return $this->selectSum('jumlah_siswa')
                    ->where('is_active', 1)
                    ->first()['jumlah_siswa'] ?? 0;
    }
}
