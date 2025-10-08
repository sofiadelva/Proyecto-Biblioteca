<?php

namespace App\Models;

use CodeIgniter\Model;

class EjemplarModel extends Model
{
    // Nombre de la tabla en la base de datos que este modelo representa
    protected $table      = 'ejemplares';
    // Nombre de la columna que actúa como clave primaria en la tabla
    protected $primaryKey = 'ejemplar_id';
    
    // 🌟 ¡SOLUCIÓN! Agregamos 'no_copia' a los campos permitidos.
    // También he eliminado 'codigo' ya que no lo hemos usado en los controladores.
    protected $allowedFields = [
        'libro_id', 
        'estado', 
        'no_copia' // ⬅️ ¡Este es el campo que faltaba!
    ];

    // Opcional: Si tienes más campos en la tabla ejemplares que necesites modificar, agrégalos aquí.
}