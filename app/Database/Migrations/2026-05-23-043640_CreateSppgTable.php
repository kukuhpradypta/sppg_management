<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSppgTable extends Migration
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
            'nama_sppg' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'penanggung_jawab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nomor_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('sppg');
    }

    public function down()
    {
        $this->forge->dropTable('sppg');
    }
}
