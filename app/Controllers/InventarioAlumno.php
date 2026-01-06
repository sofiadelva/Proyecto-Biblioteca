<?php

namespace App\Controllers;

use App\Models\LibroModel; 
use App\Controllers\BaseController;

class InventarioAlumno extends BaseController
{
    public function index()
    {
        $libroModel = new LibroModel();
        
        // 1. Obtener parámetros de búsqueda y filtros
        $buscar = $this->request->getGet('buscar');
        $coleccion_id = $this->request->getGet('coleccion_id');
        $subgenero_id = $this->request->getGet('subgenero_id');
        
        // Ley: Siempre 10 filas por página para el alumno
        $perPage = 10; 

        // 2. Construcción de la consulta con JOINs EN CADENA (Igual a tu ejemplo)
        // Libros -> Subcategorías -> Subgéneros -> Colecciones
        $builder = $libroModel
            ->select('
                libros.*, 
                subcategorias.nombre as subcategoria_nombre,
                subgeneros.nombre as subgenero_nombre,
                colecciones.nombre as coleccion_nombre
            ')
            ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
            ->join('subgeneros', 'subgeneros.subgenero_id = subcategorias.subgenero_id', 'left')
            ->join('colecciones', 'colecciones.coleccion_id = subgeneros.coleccion_id', 'left');

        // 3. Aplicar Búsqueda (Título o Autor)
        if ($buscar) {
            $builder->groupStart()
                ->like('libros.titulo', $buscar, 'both')
                ->orLike('libros.autor', $buscar, 'both')
                ->groupEnd();
        }

        // 4. Aplicar Filtros de Clasificación (Usando las tablas del JOIN)
        if (!empty($coleccion_id)) {
            $builder->where('colecciones.coleccion_id', $coleccion_id);
        }
        if (!empty($subgenero_id)) {
            $builder->where('subgeneros.subgenero_id', $subgenero_id);
        }

        // Orden predeterminado por título
        $builder->orderBy('libros.titulo', 'ASC');

        // 5. Preparar datos para la vista
        $data = [
            'libros'           => $builder->paginate($perPage, 'default'),
            'pager'            => $libroModel->pager,
            'buscar'           => $buscar,
            'coleccion_id_sel' => $coleccion_id,
            'subgenero_id_sel' => $subgenero_id,
        ];

        return view('Alumno/inventario', $data); 
    }
}