<?php

namespace App\Controllers;

use App\Models\LibroModel; 
use App\Models\CategoriaModel; 

class Inventario extends BaseController
{
    public function index()
    {
        $libroModel = new LibroModel();
        $categoriaModel = new CategoriaModel(); 
        
        // --- 1. Obtener Parámetros de la URL ---
        $buscar = $this->request->getGet('buscar');
        $ordenar = $this->request->getGet('ordenar');
        $categoriaId = $this->request->getGet('categoria_id'); // Filtro de Categoría
        
        // Obtener el número de filas por página, default a 10
        $perPage = (int)$this->request->getGet('per_page') ?? 10;
        // Asegurarse de que perPage sea un número válido y razonable
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10; 

        // --- 2. Iniciar Query Base y Filtrar por 'Disponible' (Fijo) ---
        $query = $libroModel
            ->select('libros.*, categorias.nombre as categoria')
            ->join('categorias', 'categorias.categoria_id = libros.categoria_id', 'left')
            // Filtrar SIEMPRE por libros disponibles
            ->where('libros.estado', 'Disponible');

        // --- 3. Aplicar Búsqueda (título o autor) ---
        if (!empty($buscar)) {
            $query->groupStart()
                  ->like('libros.titulo', $buscar)
                  ->orLike('libros.autor', $buscar)
                  ->groupEnd();
        }
        
        // --- 4. Aplicar Filtro de Categoría ---
        if (!empty($categoriaId)) {
            $query->where('libros.categoria_id', $categoriaId);
        }

        // --- 5. Aplicar Ordenamiento ---
        if (!empty($ordenar)) {
            switch ($ordenar) {
                case 'titulo_asc': $query->orderBy('libros.titulo', 'ASC'); break;
                case 'titulo_desc': $query->orderBy('libros.titulo', 'DESC'); break;
                case 'autor_asc': $query->orderBy('libros.autor', 'ASC'); break;
                case 'autor_desc': $query->orderBy('libros.autor', 'DESC'); break;
                case 'reciente': $query->orderBy('libros.libro_id', 'DESC'); break; 
                case 'viejo': $query->orderBy('libros.libro_id', 'ASC'); break;
                default: $query->orderBy('libros.titulo', 'ASC'); 
            }
        } else {
            $query->orderBy('libros.titulo', 'ASC');
        }

        // --- 6. Paginación y Envío de Datos ---
        $data = [
            'libros' => $query->paginate($perPage, 'default', $this->request->getVar('page')),
            'pager' => $libroModel->pager, 
            'buscar' => $buscar, 
            'categorias' => $categoriaModel->findAll(), 
            'categoriaId' => $categoriaId, 
            'perPage' => $perPage,
            // $cantidadDisponible ya no se incluye
        ];

        return view('Bibliotecario/inventario', $data); 
    }
}