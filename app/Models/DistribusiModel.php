<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiModel extends Model
{
    protected $table            = 'distribusi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tanggal_distribusi', 'sppg_id', 'sekolah_id',
        'menu_id', 'jumlah_porsi', 'estimasi_total_biaya',
        'catatan', 'status',
    ];

    protected $validationRules = [
        'tanggal_distribusi' => 'required|valid_date',
        'sppg_id'            => 'required|integer',
        'sekolah_id'         => 'required|integer',
        'menu_id'            => 'required|integer',
        'jumlah_porsi'       => 'required|integer|greater_than[0]',
    ];

    public function getWithRelations($sppgId = null)
    {
        $builder = $this->select('distribusi.*, sppg.nama_sppg, sekolah.nama_sekolah, menu_harian.nama_menu, menu_harian.estimasi_harga_per_porsi')
                        ->join('sppg', 'sppg.id = distribusi.sppg_id')
                        ->join('sekolah', 'sekolah.id = distribusi.sekolah_id')
                        ->join('menu_harian', 'menu_harian.id = distribusi.menu_id');

        if ($sppgId) {
            $builder->where('distribusi.sppg_id', $sppgId);
        }

        return $builder->orderBy('distribusi.tanggal_distribusi', 'DESC')
                       ->orderBy('distribusi.created_at', 'DESC');
    }

    public function getTodayStats($sppgId = null)
    {
        $builder = $this->select('COUNT(*) as total_distribusi, COALESCE(SUM(jumlah_porsi), 0) as total_porsi, COALESCE(SUM(estimasi_total_biaya), 0) as total_biaya')
                        ->where('tanggal_distribusi', date('Y-m-d'));

        if ($sppgId) {
            $builder->where('sppg_id', $sppgId);
        }

        return $builder->first();
    }

    public function getTopSekolah($limit = 5)
    {
        return $this->select('sekolah.nama_sekolah, SUM(distribusi.jumlah_porsi) as total_porsi')
                    ->join('sekolah', 'sekolah.id = distribusi.sekolah_id')
                    ->where('distribusi.tanggal_distribusi >=', date('Y-m-01'))
                    ->groupBy('distribusi.sekolah_id')
                    ->orderBy('total_porsi', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getWeeklyData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $stats = $this->select('COALESCE(SUM(jumlah_porsi), 0) as total_porsi, COALESCE(SUM(estimasi_total_biaya), 0) as total_biaya, COUNT(*) as total_distribusi')
                         ->where('tanggal_distribusi', $date)
                         ->first();
            $data[] = [
                'date'             => date('d M', strtotime($date)),
                'full_date'        => $date,
                'total_porsi'      => (int) ($stats['total_porsi'] ?? 0),
                'total_biaya'      => (float) ($stats['total_biaya'] ?? 0),
                'total_distribusi' => (int) ($stats['total_distribusi'] ?? 0),
            ];
        }
        return $data;
    }

    public function getMonthlyBiaya($months = 6)
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $startDate = date('Y-m-01', strtotime("-{$i} months"));
            $endDate   = date('Y-m-t', strtotime("-{$i} months"));
            $stats = $this->select('COALESCE(SUM(estimasi_total_biaya), 0) as total_biaya')
                         ->where('tanggal_distribusi >=', $startDate)
                         ->where('tanggal_distribusi <=', $endDate)
                         ->first();
            $data[] = [
                'month'      => date('M Y', strtotime($startDate)),
                'short'      => strtoupper(date('M', strtotime($startDate))),
                'total_biaya' => (float) ($stats['total_biaya'] ?? 0),
            ];
        }
        return $data;
    }
}
