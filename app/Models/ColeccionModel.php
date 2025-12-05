<?php 
namespace App\Models;

use CodeIgniter\Model;

class ColeccionModel extends Model
{
    protected $table      = 'colecciones';
    protected $primaryKey = 'coleccion_id';
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // Asumiendo que no necesitas eliminación suave

    protected $allowedFields = ['nombre'];

    // Timestamps
    protected $useTimestamps = false; 
}