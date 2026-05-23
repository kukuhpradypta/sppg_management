<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuHarianModel extends Model
{
    protected $table            = 'menu_harian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'sppg_id', 'nama_menu', 'deskripsi',
        'tanggal_menu', 'estimasi_harga_per_porsi', 'foto_menu',
    ];

    protected $validationRules = [
        'sppg_id'                  => 'required|integer',
        'nama_menu'                => 'required|max_length[200]',
        'tanggal_menu'             => 'required|valid_date',
        'estimasi_harga_per_porsi' => 'required|numeric|greater_than[0]',
    ];

    public function getWithSppg($sppgId = null)
    {
        $builder = $this->select('menu_harian.*, sppg.nama_sppg')
                        ->join('sppg', 'sppg.id = menu_harian.sppg_id');

        if ($sppgId) {
            $builder->where('menu_harian.sppg_id', $sppgId);
        }

        return $builder->orderBy('menu_harian.tanggal_menu', 'DESC');
    }

    public function getTodayMenu($sppgId = null)
    {
        $builder = $this->select('menu_harian.*, sppg.nama_sppg')
                        ->join('sppg', 'sppg.id = menu_harian.sppg_id')
                        ->where('menu_harian.tanggal_menu', date('Y-m-d'));

        if ($sppgId) {
            $builder->where('menu_harian.sppg_id', $sppgId);
        }

        return $builder->findAll();
    }

    public function getMenuBySppgAndDate($sppgId, $date)
    {
        return $this->where('sppg_id', $sppgId)
                    ->where('tanggal_menu', $date)
                    ->findAll();
    }
}
