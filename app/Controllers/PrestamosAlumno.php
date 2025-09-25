<?php

namespace App\Controllers;

use App\Models\PrestamoAlumnoModel;

class PrestamosAlumno extends BaseController
{
    public function index()
    {
        // ID del alumno logueado desde la sesión
        $usuarioId = session()->get('usuario_id');

        $prestamoModel = new PrestamoAlumnoModel();

        $prestamos = $prestamoModel
            ->select('
                libros.titulo,
                ejemplares.no_copia,
                usuarios.nombre as alumno,
                prestamos.fecha_prestamo,
                prestamos.fecha_de_devolucion,
                prestamos.fecha_devuelto,
                prestamos.estado
            ')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->where('prestamos.usuario_id', $usuarioId) // 🔹 Solo préstamos del alumno
            ->orderBy('prestamos.fecha_prestamo', 'DESC')
            ->findAll();

        return view('Alumno/prestamos_alumno', [
            'prestamos' => $prestamos
        ]);
    }
}
