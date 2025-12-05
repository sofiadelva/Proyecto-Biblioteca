<?php
namespace App\Models;

use CodeIgniter\Model;

class LibroModel extends Model
{
    protected $table      = 'libros';
    protected $primaryKey = 'libro_id';

    protected $allowedFields = [
    'codigo',
    'titulo',
    'autor',
    'editorial',
    'subcategoria_id',
    'paginas',
    'ano',
    'cantidad_total',
    'cantidad_disponibles',
    'estado'
    ];
}