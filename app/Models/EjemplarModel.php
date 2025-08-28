<?php

namespace App\Models;

use CodeIgniter\Model;

class EjemplarModel extends Model
{
    protected $table      = 'ejemplares';
    protected $primaryKey = 'ejemplar_id';
    protected $allowedFields = ['libro_id', 'codigo', 'estado'];
}
