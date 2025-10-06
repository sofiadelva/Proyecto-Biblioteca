<?php
namespace App\Controllers;

use App\Models\LibroModel;
use App\Models\CategoriaModel;
use App\Models\EjemplarModel; 
use CodeIgniter\Controller;

class Libros extends Controller
{
    protected $libroModel;
    protected $categoriaModel;
    protected $ejemplarModel;

    public function __construct()
    {
        // Instanciamos los modelos
        $this->libroModel = new LibroModel();
        $this->categoriaModel = new CategoriaModel();
        $this->ejemplarModel = new EjemplarModel();
    }

    // Listar todos los libros con paginación, filtros y ordenación
    public function index()
    {
        // GEMINI: Configuración inicial de paginación
        $defaultPerPage = 10; // Número de filas por defecto
        
        // Obtener parámetros GET
        $ordenar = $this->request->getGet('ordenar');
        $estado = $this->request->getGet('estado');
        $cantidad_disponible = $this->request->getGet('cantidad_disponible');
        
        // GEMINI: Obtener el parámetro 'per_page' y asegurar que es un entero.
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        // CORRECCIÓN DE ERROR DE DIVISIÓN POR CERO: Aseguramos que $perPage sea al menos 1.
        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        // GEMINI: Obtener el término de búsqueda
        $buscar = $this->request->getGet('buscar'); 

        // Traemos libros con JOIN para obtener el nombre de la categoría
        $builder = $this->libroModel
            ->select('libros.*, categorias.nombre as categoria')
            ->join('categorias', 'categorias.categoria_id = libros.categoria_id');
        
        // GEMINI: Aplicar la búsqueda por título o autor si existe un término
        if ($buscar) {
            $builder = $builder->groupStart() // Abrir grupo OR
                               ->like('titulo', $buscar, 'both')
                               ->orLike('autor', $buscar, 'both')
                               ->groupEnd(); // Cerrar grupo OR
        }

        // Filtrar por estado
        if ($estado) {
            $builder = $builder->where('estado', $estado);
        }

        // Filtrar por cantidad disponible
        if ($cantidad_disponible !== '' && $cantidad_disponible !== null) {
            if ($cantidad_disponible == '0') {
                $builder = $builder->where('cantidad_disponibles', 0);
            } else {
                $builder = $builder->where('cantidad_disponibles >', 0);
            }
        }

        // Ordenar
        if ($ordenar) {
            switch ($ordenar) {
                case 'titulo_asc':
                    $builder = $builder->orderBy('titulo', 'ASC');
                    break;
                case 'titulo_desc':
                    $builder = $builder->orderBy('titulo', 'DESC');
                    break;
                case 'autor_asc':
                    $builder = $builder->orderBy('autor', 'ASC');
                    break;
                case 'autor_desc':
                    $builder = $builder->orderBy('autor', 'DESC');
                    break;
                case 'reciente':
                    $builder = $builder->orderBy('libro_id', 'DESC'); // más reciente primero
                    break;
                case 'viejo':
                    $builder = $builder->orderBy('libro_id', 'ASC'); // más viejo primero
                    break;
            }
        }
        
        // GEMINI: 1. Usamos paginate() para limitar los resultados. $perPage está garantizado como entero >= 1
        $data['libros'] = $builder->paginate($perPage, 'default');
        
        // GEMINI: 2. Obtenemos el objeto Pager y el valor perPage y el término de búsqueda
        $data['pager'] = $this->libroModel->pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar; // Pasamos el término de búsqueda para rellenar el input

        // Mostramos la vista
        return view('Administrador/libros', $data);
    }

    // Mostrar formulario de nuevo libro
    public function new()
    {
        // Ya no se pasan categorías, se cargan por AJAX
        return view('Administrador/Libros/nuevo'); 
    }

    // MÉTODO JSON: Retorna categorías en formato JSON para Select2/Búsqueda dinámica
    public function get_categorias_json()
    {
        $term = $this->request->getGet('term'); // Término de búsqueda enviado por Select2
        $id = $this->request->getGet('id'); // ID de categoría para precargar (si existe)
        $categorias = [];

        $builder = $this->categoriaModel;

        if ($id) {
            // Caso para precargar una categoría por ID (después de un error de validación)
            $categorias = $builder->where('categoria_id', $id)->findAll();
        } elseif ($term) {
            // Búsqueda por término
            $categorias = $builder->like('nombre', $term, 'both')
                               ->findAll(10); // Limitar a 10 resultados para no sobrecargar
        } else {
            // Cargar los primeros 5 por defecto si no hay búsqueda
            $categorias = $builder->findAll(5); 
        }

        $results = [];
        foreach ($categorias as $cat) {
            $results[] = [
                'id' => $cat['categoria_id'],
                'text' => $cat['nombre']
            ];
        }

        // Retornar la respuesta en formato JSON
        return $this->response->setJSON(['results' => $results]);
    }


    // Guardar libro en la base de datos
    public function create()
    {
        // 1. Recoger datos del POST
        $dataLibro = [
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => (int)$this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => (int)$this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
            'categoria_id' => $this->request->getPost('categoria_id')
        ];

        // Validación simple para asegurar consistencia
        $dataLibro['cantidad_disponibles'] = min($dataLibro['cantidad_disponibles'], $dataLibro['cantidad_total']);


        // 2. Guardar el nuevo libro e obtener su ID
        $this->libroModel->insert($dataLibro);
        $nuevoLibroId = $this->libroModel->getInsertID(); // 👈 Obtenemos el ID del libro recién insertado
        $cantidadTotal = $dataLibro['cantidad_total'];
        $cantidadDisponibles = $dataLibro['cantidad_disponibles'];
        
        // 3. Crear los ejemplares según la cantidad total y diferenciar los disponibles
        if ($nuevoLibroId && $cantidadTotal > 0) {
            $ejemplares = [];
            
            // Los primeros N ejemplares serán 'Disponible'
            for ($i = 0; $i < $cantidadTotal; $i++) {
                
                // Si el índice es menor que la cantidad disponible, el ejemplar está 'Disponible'
                if ($i < $cantidadDisponibles) {
                    $estado_ejemplar = 'Disponible';
                } else {
                    $estado_ejemplar = 'Dañado'; 
                }

                $ejemplares[] = [
                    'libro_id' => $nuevoLibroId,
                    'estado'   => $estado_ejemplar 
                ];
            }
            
            // Inserción masiva de ejemplares
            $this->ejemplarModel->insertBatch($ejemplares);
        }

        // 4. Redirigir y mostrar mensaje de éxito
        return redirect()->to(base_url('libros'))->with('msg', 'Libro y sus ejemplares iniciales creados correctamente.');
    }

    // Mostrar formulario de edición de un libro
    public function edit($id)
    {
        $data['libro'] = $this->libroModel->find($id);      // libro actual
        $data['categorias'] = $this->categoriaModel->findAll();  // todas las categorías
        return view('Administrador/Libros/edit', $data);
    }

    // Actualizar un libro
    public function update($id)
    {
        $this->libroModel->update($id, [
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
            'categoria_id' => $this->request->getPost('categoria_id')
        ]);
        return redirect()->to(base_url('libros'))->with('msg', 'Libro actualizado correctamente.');
    }

    public function delete($id = null)
    {
        $libroModel = new LibroModel();

        // Verifica que el libro existe
        $libro = $libroModel->find($id);
        if (!$libro) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("El libro con ID $id no existe");
        }

        // Eliminamos todos sus ejemplares asociados antes de eliminar el libro
        $this->ejemplarModel->where('libro_id', $id)->delete();
        
        // Elimina el libro
        $libroModel->delete($id);

        // Redirige con mensaje de éxito
        return redirect()->to(base_url('libros'))->with('msg', 'Libro eliminado correctamente.');
    }
}