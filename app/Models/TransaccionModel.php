<?php

namespace App\Models;
use CodeIgniter\Model;

class TransaccionModel extends Model
{
    protected $table = 'prestamos'; // seguimos usando la tabla prestamos
    protected $primaryKey = 'prestamo_id';
    protected $allowedFields = [
        'libro_id',
        'ejemplar_id',
        'usuario_id',
        'fecha_prestamo',
        'fecha_de_devolucion',
        'fecha_devuelto',
        'estado'
    ];
}
