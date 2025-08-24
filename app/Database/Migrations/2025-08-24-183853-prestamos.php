<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrestamosTable extends Migration
{
    public function up()
    {
        $fields = [
            'prestamo_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'libro_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'ejemplar_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'fecha_prestamo' => [
                'type' => 'DATE',
            ],
            'fecha_de_devolucion' => [
                'type' => 'DATE',
            ],
            'fecha_devuelto' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('prestamo_id', true);
        $this->forge->createTable('prestamos', true);

        $this->forge->addForeignKey('libro_id', 'libros', 'libro_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ejemplar_id', 'ejemplares', 'ejemplar_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'usuario_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('prestamos', true);
    }
}
