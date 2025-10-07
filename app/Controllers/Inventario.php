<?php

namespace App\Controllers;

use App\Models\LibroModel; // Asegúrate de que este sea el nombre correcto de tu modelo

class Inventario extends BaseController
{
    public function index()
    {
        $libroModel = new LibroModel();
        
        // --- 1. Obtener Parámetros de la URL ---
        $buscar = $this->request->getGet('buscar');
        $ordenar = $this->request->getGet('ordenar');
        $estado = $this->request->getGet('estado');
        $cantidadDisponible = $this->request->getGet('cantidad_disponible');
        $perPage = 10; // Número de filas por página por defecto

        // --- 2. Iniciar Query Base ---
        // Excluir libros que estén marcados como 'Inactivo' (si tienes ese estado)
        $query = $libroModel
            ->select('libros.*, categorias.nombre as categoria')
            ->join('categorias', 'categorias.categoria_id = libros.categoria_id', 'left');
            // Nota: Se elimina el where('libros.estado', 'Disponible') estático para usar el filtro dinámico

        // --- 3. Aplicar Búsqueda (título o autor) ---
        if (!empty($buscar)) {
            $query->groupStart()
                  ->like('libros.titulo', $buscar)
                  ->orLike('libros.autor', $buscar)
                  ->groupEnd();
        }
        
        // --- 4. Aplicar Filtro de Estado (Disponible, Dañado, etc.) ---
        if (!empty($estado)) {
            $query->where('libros.estado', $estado);
        }

        // --- 5. Aplicar Filtro de Cantidad Disponible ---
        if ($cantidadDisponible !== null && $cantidadDisponible !== '') {
            if ($cantidadDisponible == '0') {
                $query->where('libros.cantidad_disponibles', 0);
            } else if ($cantidadDisponible == '1') {
                $query->where('libros.cantidad_disponibles >', 0);
            }
        }
        
        // Si no se aplicó ningún filtro de estado, mostramos *solo* los disponibles (comportamiento por defecto)
        if (empty($estado)) {
             $query->where('libros.estado', 'Disponible');
        }

        // --- 6. Aplicar Ordenamiento ---
        if (!empty($ordenar)) {
            switch ($ordenar) {
                case 'titulo_asc': $query->orderBy('libros.titulo', 'ASC'); break;
                case 'titulo_desc': $query->orderBy('libros.titulo', 'DESC'); break;
                case 'autor_asc': $query->orderBy('libros.autor', 'ASC'); break;
                case 'autor_desc': $query->orderBy('libros.autor', 'DESC'); break;
                case 'reciente': $query->orderBy('libros.libro_id', 'DESC'); break; // Asumiendo ID es autoincremental
                case 'viejo': $query->orderBy('libros.libro_id', 'ASC'); break;
                default: $query->orderBy('libros.titulo', 'ASC'); // Orden por defecto
            }
        } else {
            $query->orderBy('libros.titulo', 'ASC'); // Orden por defecto si no se especifica
        }

        // --- 7. Paginación y Envío de Datos ---
        $data = [
            'libros' => $query->paginate($perPage), // Usar paginate() en lugar de findAll()
            'pager' => $libroModel->pager,          // Pasar el objeto pager a la vista
            'buscar' => $buscar,                     // Mantener el valor de búsqueda
            // Los otros parámetros se mantienen en la URL a través de la vista
        ];

        // Asegúrate de usar la ruta de vista correcta (ej. 'Bibliotecario/inventario')
        return view('Bibliotecario/inventario', $data); 
    }
}