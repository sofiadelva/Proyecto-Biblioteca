<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        $fields = [
            'categoria_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('categoria_id', true);
        $this->forge->createTable('categorias', true);
    }

    public function down()
    {
        $this->forge->dropTable('categorias', true);
    }
}
