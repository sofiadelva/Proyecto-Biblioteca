<?php 
namespace App\Models;

use CodeIgniter\Model;

class SubgeneroModel extends Model
{
    protected $table      = 'subgeneros';
    protected $primaryKey = 'subgenero_id';
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'coleccion_id']; // coleccion_id es la FK

    // Timestamps
    protected $useTimestamps = false; 
}