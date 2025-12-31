<?php

namespace App\Models;

use CodeIgniter\Model;

class LibroModel extends Model
{
    protected $table      = 'libros';
    protected $primaryKey = 'libro_id';

    protected $allowedFields = [
        'codigo', 'titulo', 'autor', 'editorial', 
        'paginas', 'ano', 'subcategoria_id', 
        'cantidad_total', 'cantidad_disponibles'
    ];


    protected $validationRules = [
        'libro_id' => 'permit_empty',
        'titulo' => 'required|min_length[3]',
        'autor'  => 'required',
        'codigo' => 'required|is_unique[libros.codigo,libro_id,{libro_id}]',
        'cantidad_total' => 'required|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'titulo' => [
            'required' => 'El título del libro es obligatorio.',
        ],
        'codigo' => [
            'is_unique' => 'Ese código/ISBN ya está registrado con otro libro.'
        ]
    ];
}