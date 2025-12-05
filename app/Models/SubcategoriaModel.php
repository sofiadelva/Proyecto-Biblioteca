<?php 
namespace App\Models;

use CodeIgniter\Model;

class SubcategoriaModel extends Model
{
    protected $table      = 'subcategorias';
    protected $primaryKey = 'subcategoria_id';
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'subgenero_id']; // subgenero_id es la FK

    // Timestamps
    protected $useTimestamps = false; 
}