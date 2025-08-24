<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEjemplaresTable extends Migration
{
    public function up()
    {
        $fields = [
            'ejemplar_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'libro_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('ejemplar_id', true);
        $this->forge->createTable('ejemplares', true);

        // Agregar columna no_copia si no existe
        if (!$this->db->fieldExists('no_copia', 'ejemplares')) {
            $this->forge->addColumn('ejemplares', [
                'no_copia' => [
                    'type' => 'INT',
                    'null' => false,
                    'default' => 1
                ]
            ]);
        }

        // Agregar FK libro_id
        $this->forge->addForeignKey('libro_id', 'libros', 'libro_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('ejemplares', true);
    }
}
