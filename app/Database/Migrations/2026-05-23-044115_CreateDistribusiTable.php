<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDistribusiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal_distribusi' => [
                'type' => 'DATE',
            ],
            'sppg_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sekolah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'menu_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah_porsi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'estimasi_total_biaya' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['preparing', 'in_transit', 'delivered', 'cancelled'],
                'default'    => 'preparing',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sppg_id', 'sppg', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sekolah_id', 'sekolah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('menu_id', 'menu_harian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('distribusi');
    }

    public function down()
    {
        $this->forge->dropTable('distribusi');
    }
}
