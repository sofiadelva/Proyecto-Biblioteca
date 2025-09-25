<?php

namespace App\Controllers;

use App\Models\LibroModel;

class InventarioAlumno extends BaseController
{
    public function index()
    {
        $libroModel = new LibroModel();

        // Iniciar query con JOIN a categorías (solo libros disponibles)
        $query = $libroModel
            ->select('libros.*, categorias.nombre as categoria')
            ->join('categorias', 'categorias.categoria_id = libros.categoria_id', 'left')
            ->where('libros.estado', 'Disponible');

        // Búsqueda por título o autor
        $buscar = $this->request->getGet('buscar');
        if (!empty($buscar)) {
            $query->groupStart()
                  ->like('libros.titulo', $buscar)
                  ->orLike('libros.autor', $buscar)
                  ->groupEnd();
        }

        // Ordenamiento
        $ordenar = $this->request->getGet('ordenar');
        if (!empty($ordenar)) {
            switch ($ordenar) {
                case 'titulo_asc':
                    $query->orderBy('libros.titulo', 'ASC');
                    break;
                case 'titulo_desc':
                    $query->orderBy('libros.titulo', 'DESC');
                    break;
                case 'autor_asc':
                    $query->orderBy('libros.autor', 'ASC');
                    break;
                case 'autor_desc':
                    $query->orderBy('libros.autor', 'DESC');
                    break;
                case 'reciente':
                    $query->orderBy('libros.libro_id', 'DESC');
                    break;
                case 'viejo':
                    $query->orderBy('libros.libro_id', 'ASC');
                    break;
            }
        }

        // Pasar resultados y búsqueda a la vista
        $data = [
            'libros' => $query->findAll(),
            'buscar' => $buscar
        ];

        return view('Alumno/inventario', $data);
    }
}

