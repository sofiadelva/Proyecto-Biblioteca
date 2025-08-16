<?php 
namespace App\Models;

use CodeIgniter\Model;

class LibrosModel extends Model{
    protected $table      = 'libros';
    protected $primaryKey = 'libro_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['titulo', 'autor','editorial', 'cantidad_total','cantidad_disponibles', 'estado','categoria_id'];

// Uncomment below if you want add primary key
   protected $useTimestamps = false; 
    // protected $primaryKey = 'id';
}