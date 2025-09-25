<?php
namespace App\Models;

// Importamos la clase base Model de CodeIgniter
use CodeIgniter\Model;

class CategoriaModel extends Model
{
    // Nombre de la tabla en la base de datos que este modelo representa
    protected $table = 'categorias';
    // Nombre de la columna que actúa como clave primaria en la tabla
    protected $primaryKey = 'categoria_id';
    protected $allowedFields = ['nombre'];
}
