<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Seed SPPG
        $sppgData = [
            [
                'nama_sppg'        => 'SPPG Kebayoran Baru',
                'alamat'           => 'Jl. Wijaya I No.10, Kebayoran Baru, Jakarta Selatan',
                'penanggung_jawab' => 'Budi Santoso',
                'nomor_telepon'    => '081234567890',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sppg'        => 'SPPG Tebet Timur',
                'alamat'           => 'Jl. Tebet Raya No.25, Tebet, Jakarta Selatan',
                'penanggung_jawab' => 'Siti Rahayu',
                'nomor_telepon'    => '081234567891',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sppg'        => 'SPPG Kuningan City',
                'alamat'           => 'Jl. Prof. Dr. Satrio No.18, Kuningan, Jakarta Selatan',
                'penanggung_jawab' => 'Ahmad Fauzi',
                'nomor_telepon'    => '081234567892',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('sppg')->insertBatch($sppgData);

        // Seed Sekolah
        $sekolahData = [
            [
                'nama_sekolah' => 'SD Negeri 01 Menteng',
                'alamat'       => 'Jl. Besuki No.4, Menteng, Jakarta Pusat',
                'jenjang'      => 'SD',
                'jumlah_siswa' => 450,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sekolah' => 'SMP Negeri 115 Jakarta',
                'alamat'       => 'Jl. KH Abdullah Syafei, Tebet, Jakarta Selatan',
                'jenjang'      => 'SMP',
                'jumlah_siswa' => 820,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sekolah' => 'TK Al-Azhar Pusat',
                'alamat'       => 'Jl. Sisingamangaraja, Kebayoran Baru',
                'jenjang'      => 'TK',
                'jumlah_siswa' => 120,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sekolah' => 'SMA Negeri 70 Jakarta',
                'alamat'       => 'Jl. Bulungan No.1, Kebayoran Baru',
                'jenjang'      => 'SMA',
                'jumlah_siswa' => 1050,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nama_sekolah' => 'SD Negeri 05 Pagi',
                'alamat'       => 'Jl. Pejaten Barat No.3, Pasar Minggu',
                'jenjang'      => 'SD',
                'jumlah_siswa' => 380,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('sekolah')->insertBatch($sekolahData);

        // Seed Users
        $usersData = [
            [
                'username'     => 'admin',
                'email'        => 'admin@sppg.go.id',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Administrator SPPG',
                'role'         => 'admin',
                'sppg_id'      => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'sppg_kebayoran',
                'email'        => 'kebayoran@sppg.go.id',
                'password'     => password_hash('sppg123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Operator SPPG Kebayoran',
                'role'         => 'sppg',
                'sppg_id'      => 1,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'sppg_tebet',
                'email'        => 'tebet@sppg.go.id',
                'password'     => password_hash('sppg123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Operator SPPG Tebet',
                'role'         => 'sppg',
                'sppg_id'      => 2,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($usersData);

        // Seed Menu Harian
        $menuData = [
            [
                'sppg_id'                  => 1,
                'nama_menu'                => 'Nasi Ayam Teriyaki + Susu + Buah Pisang',
                'deskripsi'                => 'Nasi putih, ayam teriyaki, susu kotak, buah pisang',
                'tanggal_menu'             => date('Y-m-d'),
                'estimasi_harga_per_porsi' => 15000.00,
                'created_at'               => date('Y-m-d H:i:s'),
                'updated_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'sppg_id'                  => 1,
                'nama_menu'                => 'Nasi Goreng Spesial + Jus Jeruk',
                'deskripsi'                => 'Nasi goreng dengan telur, ayam suwir, jus jeruk',
                'tanggal_menu'             => date('Y-m-d'),
                'estimasi_harga_per_porsi' => 13000.00,
                'created_at'               => date('Y-m-d H:i:s'),
                'updated_at'               => date('Y-m-d H:i:s'),
            ],
            [
                'sppg_id'                  => 2,
                'nama_menu'                => 'Bubur Ayam + Telur Rebus + Susu Kedelai',
                'deskripsi'                => 'Bubur ayam spesial, telur rebus, susu kedelai',
                'tanggal_menu'             => date('Y-m-d'),
                'estimasi_harga_per_porsi' => 12000.00,
                'created_at'               => date('Y-m-d H:i:s'),
                'updated_at'               => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('menu_harian')->insertBatch($menuData);

        // Seed Distribusi
        $distribusiData = [
            [
                'tanggal_distribusi'  => date('Y-m-d'),
                'sppg_id'            => 1,
                'sekolah_id'         => 1,
                'menu_id'            => 1,
                'jumlah_porsi'       => 245,
                'estimasi_total_biaya' => 245 * 15000,
                'catatan'            => 'Distribusi pagi',
                'status'             => 'delivered',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'tanggal_distribusi'  => date('Y-m-d'),
                'sppg_id'            => 1,
                'sekolah_id'         => 2,
                'menu_id'            => 1,
                'jumlah_porsi'       => 180,
                'estimasi_total_biaya' => 180 * 15000,
                'catatan'            => null,
                'status'             => 'delivered',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'tanggal_distribusi'  => date('Y-m-d'),
                'sppg_id'            => 2,
                'sekolah_id'         => 4,
                'menu_id'            => 3,
                'jumlah_porsi'       => 320,
                'estimasi_total_biaya' => 320 * 12000,
                'catatan'            => 'Distribusi siang',
                'status'             => 'in_transit',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            [
                'tanggal_distribusi'  => date('Y-m-d'),
                'sppg_id'            => 1,
                'sekolah_id'         => 3,
                'menu_id'            => 2,
                'jumlah_porsi'       => 85,
                'estimasi_total_biaya' => 85 * 13000,
                'catatan'            => null,
                'status'             => 'preparing',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('distribusi')->insertBatch($distribusiData);
    }
}
