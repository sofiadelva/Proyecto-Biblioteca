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
        
        // Aplicar la búsqueda por título, autor o código
        if ($buscar) {
            $builder = $builder->groupStart()
                ->like('libros.titulo', $buscar, 'both')
                ->orLike('libros.autor', $buscar, 'both')
                ->orLike('libros.codigo', $buscar, 'both') 
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
        $subcategoriaId = $this->request->getPost('subcategoria_id');

    // IMPORTANTE: Si viene vacío, convertirlo a NULL real
    // Esto evita el error de llave foránea
    $subcategoriaId = (!empty($subcategoriaId)) ? $subcategoriaId : null;
        // 1. Recoger datos del POST - CAMPOS ACTUALIZADOS
        $dataLibro = [
            'codigo' => $this->request->getPost('codigo'), // NUEVO CAMPO
            'titulo' => $this->request->getPost('titulo'),
            'autor'=> $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'paginas' => (int)$this->request->getPost('paginas'), // NUEVO CAMPO
            'ano' => (int)$this->request->getPost('ano'), // NUEVO CAMPO
            'subcategoria_id' => $subcategoriaId,
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

    public function edit($id)
    {
        // 1. Buscamos el libro con un JOIN simple para la subcategoría
        $libro = $this->libroModel
            ->select('libros.*, subcategorias.nombre as subcategoria_nombre')
            ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
            ->find($id);

        // Si no existe el libro, redirigimos de inmediato
        if (!$libro) {
            return redirect()->to(base_url('libros'))->with('msg_error', 'Libro no encontrado.');
        }

        // 2. Preparamos los datos base para la vista
        $data = [
            'libro'             => $libro,
            'coleccion_id'      => null,
            'coleccion_nombre'  => null,
            'subgenero_id'      => null,
            'subgenero_nombre'  => null
        ];

        // 3. Obtener jerarquía hacia arriba solo si existe subcategoria_id
        if (!empty($libro['subcategoria_id'])) {
            $subcat = $this->subcategoriaModel->find($libro['subcategoria_id']);
            
            if ($subcat && !empty($subcat['subgenero_id'])) {
                $subgen = $this->subgeneroModel->find($subcat['subgenero_id']);
                
                if ($subgen) {
                    $data['subgenero_id']     = $subgen['subgenero_id'];
                    $data['subgenero_nombre'] = $subgen['nombre'];
                    
                    if (!empty($subgen['coleccion_id'])) {
                        $col = $this->coleccionModel->find($subgen['coleccion_id']);
                        if ($col) {
                            $data['coleccion_id']     = $col['coleccion_id'];
                            $data['coleccion_nombre'] = $col['nombre'];
                        }
                    }
                }
            }
        }
        
        return view('Administrador/Libros/edit', $data);
    }

  public function update($id)
{
    // 1. Capturamos los IDs del formulario
    // IMPORTANTE: 'subgenero_id_dummy' debe existir en el HTML
    $subcategoriaId = $this->request->getPost('subcategoria_id');
    $subgeneroId    = $this->request->getPost('subgenero_id_dummy');

    // 2. Lógica del Comodín: Si no eligieron subcategoría pero sí hay subgénero
    if (empty($subcategoriaId) && !empty($subgeneroId)) {
        $comodin = $this->subcategoriaModel
            ->where('subgenero_id', $subgeneroId)
            ->groupStart()
                ->where('nombre', null)
                ->orWhere('nombre', '')
                ->orWhere('nombre', 'NULL')
            ->groupEnd()
            ->first();

        if ($comodin) {
            $subcategoriaId = $comodin['subcategoria_id'];
        } else {
            $subcategoriaId = null;
        }
    } else {
        // Si el usuario seleccionó una subcategoría manualmente, usamos esa.
        // Si todo está vacío, lo dejamos null.
        $subcategoriaId = (!empty($subcategoriaId)) ? $subcategoriaId : null;
    }

    // 3. Preparación de datos
    $dataUpdate = [
        'codigo'               => $this->request->getPost('codigo'),
        'titulo'               => $this->request->getPost('titulo'),
        'autor'                => $this->request->getPost('autor'),
        'editorial'            => $this->request->getPost('editorial'),
        'paginas'              => (int)$this->request->getPost('paginas'),
        'ano'                  => (int)$this->request->getPost('ano'),
        'subcategoria_id'      => $subcategoriaId,
        'cantidad_total'       => (int)$this->request->getPost('cantidad_total'),
        'cantidad_disponibles' => (int)$this->request->getPost('cantidad_disponibles'),
        'estado'               => $this->request->getPost('estado'),
    ];

    if ($this->libroModel->update($id, $dataUpdate)) {
        return redirect()->to(base_url('libros'))->with('msg', 'Libro actualizado correctamente.');
    } else {
        return redirect()->back()->withInput()->with('msg_error', 'No se pudo actualizar.');
    }
}
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

    public function get_colecciones_json()
    {
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id');

        if ($id) {
            $data = $this->coleccionModel->where('coleccion_id', $id)->findAll();
        } elseif (!empty($term)) {
            $data = $this->coleccionModel->like('nombre', $term)->findAll(10);
        } else {
            $data = $this->coleccionModel->findAll(10);
        }

        $results = array_map(function($item) {
            return ['id' => $item['coleccion_id'], 'text' => $item['nombre']];
        }, $data);

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Devuelve subgéneros filtrados por colección
     */
    public function get_subgeneros_json()
    {
        $coleccionId = $this->request->getGet('coleccion_id');
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id');

        $builder = $this->subgeneroModel;

        if ($id) {
            $builder->where('subgenero_id', $id);
        } else {
            if ($coleccionId) $builder->where('coleccion_id', $coleccionId);
            if (!empty($term)) $builder->like('nombre', $term);
        }

        $data = $builder->findAll(15);
        $results = array_map(function($item) {
            return ['id' => $item['subgenero_id'], 'text' => $item['nombre']];
        }, $data);

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Devuelve subcategorías filtradas por subgénero
     */
    public function get_subcategorias_json()
    {
        $subgeneroId = $this->request->getGet('subgenero_id');
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id');

        $builder = $this->subcategoriaModel;

        if ($id) {
            $builder->where('subcategoria_id', $id);
        } else {
            if ($subgeneroId) $builder->where('subgenero_id', $subgeneroId);
            if (!empty($term)) $builder->like('nombre', $term);
        }

        $data = $builder->findAll(15);
        $results = array_map(function($item) {
            return ['id' => $item['subcategoria_id'], 'text' => $item['nombre']];
        }, $data);

        return $this->response->setJSON(['results' => $results]);
    }
}