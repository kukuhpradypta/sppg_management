<?php

namespace App\Models;

use CodeIgniter\Model;

class SppgModel extends Model
{
    protected $table            = 'sppg';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'nama_sppg', 'alamat', 'penanggung_jawab',
        'nomor_telepon', 'is_active',
    ];

    protected $validationRules = [
        'nama_sppg'        => 'required|min_length[3]|max_length[150]',
        'alamat'           => 'required',
        'penanggung_jawab' => 'required|max_length[100]',
        'nomor_telepon'    => 'required|max_length[20]',
    ];

    public function getActiveSppg()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
