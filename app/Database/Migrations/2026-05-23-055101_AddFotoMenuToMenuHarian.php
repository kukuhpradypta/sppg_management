<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoMenuToMenuHarian extends Migration
{
    public function up()
    {
        $this->forge->addColumn('menu_harian', [
            'foto_menu' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'estimasi_harga_per_porsi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('menu_harian', 'foto_menu');
    }
}
