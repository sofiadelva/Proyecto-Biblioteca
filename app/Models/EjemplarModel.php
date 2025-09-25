<?php

namespace App\Models;

// Importamos la clase base Model de CodeIgniter
use CodeIgniter\Model;

class EjemplarModel extends Model
{
    // Nombre de la tabla en la base de datos que este modelo representa
    protected $table      = 'ejemplares';
    // Nombre de la columna que actúa como clave primaria en la tabla
    protected $primaryKey = 'ejemplar_id';
    protected $allowedFields = ['libro_id', 'codigo', 'estado'];
}