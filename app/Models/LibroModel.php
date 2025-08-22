<?php
namespace App\Models;

use CodeIgniter\Model;

class LibroModel extends Model
{
    protected $table      = 'libros';
    protected $primaryKey = 'libro_id';

    protected $allowedFields = [
        'titulo',
        'autor',
        'editorial',
        'cantidad_total',
        'cantidad_disponibles',
        'estado'
    ];
}