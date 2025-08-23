<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'categoria_id';
    protected $allowedFields = ['nombre'];
}
