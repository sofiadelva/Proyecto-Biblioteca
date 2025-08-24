<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        $fields = [
            'usuario_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'carne' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'rol' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('usuario_id', true);
        $this->forge->createTable('usuarios', true); // true = ifNotExists
    }

    public function down()
    {
        $this->forge->dropTable('usuarios', true); // true = ifExists
    }
}
