<?php

namespace App\Controllers;

use App\Models\DistribusiModel;
use App\Models\MenuHarianModel;
use App\Models\SekolahModel;
use App\Models\SppgModel;

class Dashboard extends BaseController
{
    protected $distribusiModel;
    protected $sekolahModel;
    protected $sppgModel;
    protected $menuModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiModel();
        $this->sekolahModel    = new SekolahModel();
        $this->sppgModel       = new SppgModel();
        $this->menuModel       = new MenuHarianModel();
    }

    public function index()
    {
        $role   = session()->get('role');
        $sppgId = session()->get('sppg_id');

        if ($role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->sppgDashboard($sppgId);
    }

    private function adminDashboard()
    {
        $data = [
            'title'            => 'Dashboard Monitoring',
            'totalSekolah'     => $this->sekolahModel->where('is_active', 1)->countAllResults(false),
            'totalSppg'        => $this->sppgModel->where('is_active', 1)->countAllResults(false),
            'todayStats'       => $this->distribusiModel->getTodayStats(),
            'recentDistribusi' => $this->distribusiModel->getWithRelations()->limit(5)->findAll(),
            'todayMenu'        => $this->menuModel->getTodayMenu(),
            'topSekolah'       => $this->distribusiModel->getTopSekolah(),
            'weeklyData'       => $this->distribusiModel->getWeeklyData(),
            'monthlyBiaya'     => $this->distribusiModel->getMonthlyBiaya(),
            'activeSppg'       => $this->sppgModel->getActiveSppg(),
            'sekolahByJenjang' => $this->sekolahModel->countByJenjang(),
        ];

        return view('dashboard/admin', $data);
    }

    private function sppgDashboard($sppgId)
    {
        $data = [
            'title'            => 'Dashboard SPPG',
            'todayStats'       => $this->distribusiModel->getTodayStats($sppgId),
            'recentDistribusi' => $this->distribusiModel->getWithRelations($sppgId)->limit(5)->findAll(),
            'todayMenu'        => $this->menuModel->getTodayMenu($sppgId),
            'sppgInfo'         => $this->sppgModel->find($sppgId),
        ];

        return view('dashboard/sppg', $data);
    }
}
