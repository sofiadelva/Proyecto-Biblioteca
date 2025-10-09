<?php

namespace App\Controllers;

use App\Models\PrestamoAlumnoModel;
use App\Controllers\BaseController;

class PrestamosAlumno extends BaseController
{
    public function index()
    {
        // ID del alumno logueado desde la sesión
        $usuarioId = session()->get('usuario_id');

        $prestamoModel = new PrestamoAlumnoModel();

        // --- 1. Obtener Parámetros de la URL ---
        $buscar = $this->request->getGet('buscar');
        $estadoFiltro = $this->request->getGet('estado_filtro'); // 'Devuelto' o 'En proceso'
        
        // Obtener el número de filas por página, default a 10
        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10; 

        // --- 2. Iniciar Query Base ---
        $query = $prestamoModel
            ->select('
                libros.titulo,
                libros.autor,
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
            ->orderBy('prestamos.fecha_prestamo', 'DESC');

        // --- 3. Aplicar Búsqueda (título o autor) ---
        if (!empty($buscar)) {
            $query->groupStart()
                  ->like('libros.titulo', $buscar)
                  ->orLike('libros.autor', $buscar)
                  ->groupEnd();
        }
        
        // --- 4. Aplicar Filtro de Estado ---
        if (!empty($estadoFiltro) && in_array($estadoFiltro, ['Devuelto', 'En proceso'])) {
            $query->where('prestamos.estado', $estadoFiltro);
        } elseif (!empty($estadoFiltro) && $estadoFiltro === 'Vencido') {
             // Opcional: Permitir filtrar también los vencidos
             $query->where('prestamos.estado', 'Vencido');
        }

        // --- 5. Paginación y Envío de Datos ---
        $data = [
            'prestamos' => $query->paginate($perPage, 'default', $this->request->getVar('page')),
            'pager' => $prestamoModel->pager, 
            'buscar' => $buscar, 
            'estadoFiltro' => $estadoFiltro, 
            'perPage' => $perPage,
        ];

        return view('Alumno/prestamos_alumno', $data); 
    }
}
