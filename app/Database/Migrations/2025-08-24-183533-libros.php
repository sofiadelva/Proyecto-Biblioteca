<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLibrosTable extends Migration
{
    public function up()
    {
        $fields = [
            'libro_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'autor' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'editorial' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'cantidad_total' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'cantidad_disponibles' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('libro_id', true);
        $this->forge->createTable('libros', true);

        // Agregar columna categoria_id si no existe
        if (!$this->db->fieldExists('categoria_id', 'libros')) {
            $this->forge->addColumn('libros', [
                'categoria_id' => [
                    'type' => 'INT',
                    'null' => true,
                ]
            ]);
            $this->forge->addForeignKey('categoria_id', 'categorias', 'categoria_id', 'CASCADE', 'CASCADE');
        }
    }

    public function down()
    {
        $this->forge->dropTable('libros', true);
    }
}
