<?php
namespace App\Controllers;

use App\Models\LibroModel;
use App\Models\CategoriaModel;
use App\Models\EjemplarModel; 
use App\Models\PrestamoModel;
use App\Models\ColeccionModel;   // <-- NUEVO: Importación del modelo Coleccion
use App\Models\SubgeneroModel;   // <-- NUEVO: Importación del modelo Subgenero
use App\Models\SubcategoriaModel; // <-- NUEVO: Importación del modelo Subcategoria
use CodeIgniter\Controller;

class Libros extends Controller
{
    protected $libroModel;
    protected $categoriaModel;
    protected $ejemplarModel;
    protected $prestamoModel;
    protected $coleccionModel;   // <-- NUEVA PROPIEDAD
    protected $subgeneroModel;   // <-- NUEVA PROPIEDAD
    protected $subcategoriaModel; // <-- NUEVA PROPIEDAD

    public function __construct()
    {
        // Instanciamos los modelos
        $this->libroModel = new LibroModel();
        $this->categoriaModel = new CategoriaModel();
        $this->ejemplarModel = new EjemplarModel();
        $this->prestamoModel = new PrestamoModel();
        
        // 🌟 INSTANCIAMOS LOS MODELOS DE CLASIFICACIÓN
        $this->coleccionModel = new ColeccionModel();    
        $this->subgeneroModel = new SubgeneroModel();    
        $this->subcategoriaModel = new SubcategoriaModel(); 
    }

    /**
     * Muestra la lista de libros con filtros, paginación y JOINs para clasificación. (READ principal)
     */
    public function index()
    {
        // Configuración inicial de paginación
        $defaultPerPage = 10;
        
        // Obtener parámetros GET
        $ordenar = $this->request->getGet('ordenar');
        $estado = $this->request->getGet('estado');
        $cantidad_disponible = $this->request->getGet('cantidad_disponible');
        
        // Obtener el parámetro 'per_page' y asegurar que es un entero.
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 
        if ($perPage < 1) { $perPage = $defaultPerPage; }

        $buscar = $this->request->getGet('buscar'); 

        // 🌟 CADENA DE JOINs para obtener los nombres de Colección, Subgénero y Subcategoría
        $builder = $this->libroModel
            ->select('
                libros.*, 
                subcategorias.nombre as subcategoria_nombre,
                subgeneros.nombre as subgenero_nombre,
                colecciones.nombre as coleccion_nombre
            ') 
            // 1. JOIN a subcategorias
            ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
            // 2. JOIN a subgeneros
            ->join('subgeneros', 'subgeneros.subgenero_id = subcategorias.subgenero_id', 'left')
            // 3. JOIN a colecciones
            ->join('colecciones', 'colecciones.coleccion_id = subgeneros.coleccion_id', 'left');
        
        // Aplicar la búsqueda por título o autor
        if ($buscar) {
            $builder = $builder->groupStart()
                ->like('titulo', $buscar, 'both')
                ->orLike('autor', $buscar, 'both')
                ->groupEnd();
        }

        // Aplicar filtros (estado, cantidad)
        if ($estado) {
            $builder = $builder->where('estado', $estado);
        }
        if ($cantidad_disponible !== '' && $cantidad_disponible !== null) {
            if ($cantidad_disponible == '0') {
                $builder = $builder->where('cantidad_disponibles', 0);
            } else {
                $builder = $builder->where('cantidad_disponibles >', 0);
            }
        }

        // Aplicar ordenación
        if ($ordenar) {
            switch ($ordenar) {
                case 'titulo_asc': $builder = $builder->orderBy('titulo', 'ASC'); break;
                case 'titulo_desc': $builder = $builder->orderBy('titulo', 'DESC'); break;
                case 'autor_asc': $builder = $builder->orderBy('autor', 'ASC'); break;
                case 'autor_desc': $builder = $builder->orderBy('autor', 'DESC'); break;
                case 'reciente': $builder = $builder->orderBy('libro_id', 'DESC'); break;
                case 'viejo': $builder = $builder->orderBy('libro_id', 'ASC'); break;
                case 'ano_asc': $builder = $builder->orderBy('ano', 'ASC'); break;
                case 'ano_desc': $builder = $builder->orderBy('ano', 'DESC'); break;
            }
        }
        
        // Paginación y datos de vista
        $data['libros'] = $builder->paginate($perPage, 'default');
        $data['pager'] = $this->libroModel->pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar; 

        return view('Administrador/libros', $data);
    }
    
    // ----------------------------------------------------
    // C.R.U.D.
    // ----------------------------------------------------

    /**
     * Muestra el formulario para crear un nuevo libro. (CREATE - Formulario)
     * RUTA: /libros/new
     */
    public function new()
    {
        // Esto resuelve el error 404
        return view('Administrador/Libros/nuevo'); 
    }

    /**
     * Guardar libro en la base de datos (CREATE - Proceso)
     * RUTA: /libros/create (POST)
     */
    public function create()
    {
        // 1. Recoger datos del POST - CAMPOS ACTUALIZADOS
        $dataLibro = [
            'codigo' => $this->request->getPost('codigo'), // NUEVO CAMPO
            'titulo' => $this->request->getPost('titulo'),
            'autor'=> $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'paginas' => (int)$this->request->getPost('paginas'), // NUEVO CAMPO
            'ano' => (int)$this->request->getPost('ano'), // NUEVO CAMPO
            'subcategoria_id' => $this->request->getPost('subcategoria_id'), // CAMBIO CLAVE
            'cantidad_total' => (int)$this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => (int)$this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
        ];

        // Validación simple para asegurar consistencia
        $dataLibro['cantidad_disponibles'] = min($dataLibro['cantidad_disponibles'], $dataLibro['cantidad_total']);

        // 2. Guardar el nuevo libro e obtener su ID
        $this->libroModel->insert($dataLibro);
        $nuevoLibroId = $this->libroModel->getInsertID(); 
        $cantidadTotal = $dataLibro['cantidad_total'];
        $cantidadDisponibles = $dataLibro['cantidad_disponibles'];
        
        // 3. Crear los ejemplares según la cantidad total y diferenciar los disponibles
        if ($nuevoLibroId && $cantidadTotal > 0) {
            $ejemplares = [];
            
            for ($i = 0; $i < $cantidadTotal; $i++) {
                // Si el índice es menor que la cantidad disponible, el ejemplar está 'Disponible'
                $estado_ejemplar = ($i < $cantidadDisponibles) ? 'Disponible' : 'Dañado';

                $ejemplares[] = [
                    'libro_id' => $nuevoLibroId,
                    'estado' => $estado_ejemplar,
                    'no_copia' => $i + 1 // Asignar el número de copia de forma secuencial
                ];
            }
            
            // Inserción masiva de ejemplares
            $this->ejemplarModel->insertBatch($ejemplares);
        }

        // 4. Redirigir y mostrar mensaje de éxito
        return redirect()->to(base_url('libros'))->with('msg', 'Libro y sus ejemplares iniciales creados correctamente.');
    }

    /**
     * Muestra el formulario de edición con los datos del libro. (UPDATE - Formulario)
     * RUTA: /libros/edit/id
     */
    public function edit($id)
    {
        // 🌟 Realizamos el JOIN para obtener el subcategoria_nombre y precargar el Select2
        $data['libro'] = $this->libroModel
            ->select('libros.*, subcategorias.nombre as subcategoria_nombre')
            ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
            ->find($id);

        if (empty($data['libro'])) {
            return redirect()->to(base_url('libros'))->with('msg_error', 'Libro no encontrado.');
        }

        // 🌟 Obtener los IDs y nombres de Colección y Subgénero para precargar
        if (!empty($data['libro']['subcategoria_id'])) {
            $subcategoria = $this->subcategoriaModel->find($data['libro']['subcategoria_id']);
            if ($subcategoria) {
                $subgenero = $this->subgeneroModel->find($subcategoria['subgenero_id']);
                if ($subgenero) {
                    $coleccion = $this->coleccionModel->find($subgenero['coleccion_id']);
                    
                    $data['subgenero_id'] = $subgenero['subgenero_id'];
                    $data['subgenero_nombre'] = $subgenero['nombre'];
                    $data['coleccion_id'] = $coleccion['coleccion_id'] ?? null;
                    $data['coleccion_nombre'] = $coleccion['nombre'] ?? null;
                }
            }
        }
        
        return view('Administrador/Libros/editar', $data);
    }

    /**
     * Actualizar un libro (UPDATE - Proceso)
     * RUTA: /libros/update/id (POST)
     */
    public function update($id)
    {
        // CAMPOS ACTUALIZADOS
        $this->libroModel->update($id, [
            'codigo' => $this->request->getPost('codigo'),
            'titulo' => $this->request->getPost('titulo'),
            'autor' => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'paginas' => (int)$this->request->getPost('paginas'),
            'ano' => (int)$this->request->getPost('ano'),
            'subcategoria_id' => $this->request->getPost('subcategoria_id'), // CAMBIO CLAVE
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
        ]);
        
        // Nota: La actualización de Ejemplares (aumentar/disminuir copias)
        // se manejará en una etapa posterior para simplificar el UPDATE inicial.

        return redirect()->to(base_url('libros'))->with('msg', 'Libro actualizado correctamente.');
    }

    /**
     * Elimina un libro y todos sus ejemplares y préstamos asociados. (DELETE)
     * RUTA: /libros/delete/id
     */
    public function delete($id)
    {
        // 1. Verificar que el libro existe
        $libro = $this->libroModel->find($id);
        if (!$libro) {
            return redirect()->to(base_url('libros'))->with('msg_error', "El libro con ID $id no existe");
        }

        // 2. Obtener los IDs de todos los ejemplares (hijos) ligados a este libro.
        $ejemplares = $this->ejemplarModel->where('libro_id', $id)->findAll();
        $ejemplar_ids = array_column($ejemplares, 'ejemplar_id');

        if (!empty($ejemplar_ids)) {
            // 3. ELIMINAR PRÉSTAMOS (Nietos): Elimina todos los préstamos asociados a estos ejemplares.
            $this->prestamoModel->whereIn('ejemplar_id', $ejemplar_ids)->delete();
        }
        
        // 4. ELIMINAR EJEMPLARES (Hijos): Elimina todos los ejemplares ligados al libro.
        $this->ejemplarModel->where('libro_id', $id)->delete();
        
        // 5. ELIMINAR LIBRO (Padre)
        $this->libroModel->delete($id);

        return redirect()->to(base_url('libros'))->with('msg', 'Libro y todos sus ejemplares/préstamos relacionados eliminados correctamente.');
    }
    
    // ----------------------------------------------------
    // MÉTODOS AJAX (Select2 en cascada)
    // ----------------------------------------------------

    /**
     * Devuelve colecciones en formato JSON para Select2
     * RUTA: /libros/get_colecciones_json
     */
    public function get_colecciones_json()
    {
        $term = $this->request->getGet('term');
        $builder = $this->coleccionModel;

        if ($term) {
            $colecciones = $builder->like('nombre', $term, 'both')->findAll(10);
        } else {
            $colecciones = $builder->findAll(5); 
        }

        $results = [];
        foreach ($colecciones as $col) {
            $results[] = [
                'id' => $col['coleccion_id'],
                'text' => $col['nombre']
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Devuelve subgéneros en formato JSON, filtrados por coleccion_id
     * RUTA: /libros/get_subgeneros_json
     */
    public function get_subgeneros_json()
    {
        $coleccionId = $this->request->getGet('coleccion_id');
        $term = $this->request->getGet('term');
        
        $builder = $this->subgeneroModel;

        if ($coleccionId) {
            $builder = $builder->where('coleccion_id', $coleccionId);
        }
        
        if ($term) {
            $builder = $builder->like('nombre', $term, 'both');
        }

        $subgeneros = $builder->findAll(10); 

        $results = [];
        foreach ($subgeneros as $sg) {
            $results[] = [
                'id' => $sg['subgenero_id'],
                'text' => $sg['nombre']
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Devuelve subcategorías en formato JSON, filtradas por subgenero_id
     * RUTA: /libros/get_subcategorias_json
     */
    public function get_subcategorias_json()
    {
        $subgeneroId = $this->request->getGet('subgenero_id');
        $term = $this->request->getGet('term');
        
        $builder = $this->subcategoriaModel;

        if ($subgeneroId) {
            $builder = $builder->where('subgenero_id', $subgeneroId);
        }
        
        if ($term) {
            $builder = $builder->like('nombre', $term, 'both');
        }

        $subcategorias = $builder->findAll(10); 

        $results = [];
        foreach ($subcategorias as $sc) {
            $results[] = [
                'id' => $sc['subcategoria_id'],
                'text' => $sc['nombre']
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }
    
    // Método anterior de get_categorias_json (mantenido por compatibilidad si es necesario)
    public function get_categorias_json()
    {
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id');
        $categorias = [];

        $builder = $this->categoriaModel;

        if ($id) {
            $categorias = $builder->where('categoria_id', $id)->findAll();
        } elseif ($term) {
            $categorias = $builder->like('nombre', $term, 'both')
                                 ->findAll(10);
        } else {
            $categorias = $builder->findAll(5); 
        }

        $results = [];
        foreach ($categorias as $cat) {
            $results[] = [
                'id' => $cat['categoria_id'],
                'text' => $cat['nombre']
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }
}