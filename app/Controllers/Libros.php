<?php

namespace App\Controllers;

use App\Models\LibroModel;
use App\Models\CategoriaModel;
use App\Models\EjemplarModel;
use App\Models\PrestamoModel;
use App\Models\ColeccionModel;
use App\Models\SubgeneroModel;
use App\Models\SubcategoriaModel;
use CodeIgniter\Controller;

class Libros extends Controller
{
    protected $libroModel;
    protected $categoriaModel;
    protected $ejemplarModel;
    protected $prestamoModel;
    protected $coleccionModel;
    protected $subgeneroModel;
    protected $subcategoriaModel;

    public function __construct()
    {
        $this->libroModel = new LibroModel();
        $this->categoriaModel = new CategoriaModel();
        $this->ejemplarModel = new EjemplarModel();
        $this->prestamoModel = new PrestamoModel();
        $this->coleccionModel = new ColeccionModel();
        $this->subgeneroModel = new SubgeneroModel();
        $this->subcategoriaModel = new SubcategoriaModel();
    }

    /**
     * Index: Se eliminó el filtro de 'estado' de libros.
     */
   public function index()
{
    // Configuración inicial de paginación
    $defaultPerPage = 10;
    $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage);
    if ($perPage < 1) { $perPage = $defaultPerPage; }

    // Obtener parámetros GET de búsqueda y orden
    $buscar = $this->request->getGet('buscar');
    $ordenar = $this->request->getGet('ordenar');
    $cantidad_disponible = $this->request->getGet('cantidad_disponible');

    // 🌟 NUEVOS: Parámetros de filtrado por clasificación
    $coleccion_id = $this->request->getGet('coleccion_id');
    $subgenero_id = $this->request->getGet('subgenero_id');

    // Construcción de la consulta con JOINs
    $builder = $this->libroModel
        ->select('
            libros.*, 
            subcategorias.nombre as subcategoria_nombre,
            subgeneros.nombre as subgenero_nombre,
            colecciones.nombre as coleccion_nombre
        ')
        ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
        ->join('subgeneros', 'subgeneros.subgenero_id = subcategorias.subgenero_id', 'left')
        ->join('colecciones', 'colecciones.coleccion_id = subgeneros.coleccion_id', 'left');

    // Aplicar búsqueda global (Código, Título, Autor)
    if ($buscar) {
        $builder = $builder->groupStart()
            ->like('libros.titulo', $buscar, 'both')
            ->orLike('libros.autor', $buscar, 'both')
            ->orLike('libros.codigo', $buscar, 'both')
            ->groupEnd();
    }

    // Filtro por disponibilidad numérica
    if ($cantidad_disponible !== '' && $cantidad_disponible !== null) {
        if ($cantidad_disponible == '0') {
            $builder = $builder->where('libros.cantidad_disponibles', 0);
        } else {
            $builder = $builder->where('libros.cantidad_disponibles >', 0);
        }
    }

    // 🌟 APLICAR FILTROS DE CLASIFICACIÓN
    if (!empty($coleccion_id)) {
        $builder->where('colecciones.coleccion_id', $coleccion_id);
    }
    if (!empty($subgenero_id)) {
        $builder->where('subgeneros.subgenero_id', $subgenero_id);
    }

    // Aplicar ordenación
    if ($ordenar) {
        switch ($ordenar) {
            case 'titulo_asc': $builder->orderBy('titulo', 'ASC'); break;
            case 'titulo_desc': $builder->orderBy('titulo', 'DESC'); break;
            case 'autor_asc': $builder->orderBy('autor', 'ASC'); break;
            case 'autor_desc': $builder->orderBy('autor', 'DESC'); break;
            case 'reciente': $builder->orderBy('libro_id', 'DESC'); break;
            case 'viejo': $builder->orderBy('libro_id', 'ASC'); break;
            case 'ano_asc': $builder->orderBy('ano', 'ASC'); break;
            case 'ano_desc': $builder->orderBy('ano', 'DESC'); break;
        }
    }

    // Ejecutar paginación
    $data['libros']  = $builder->paginate($perPage, 'default');
    $data['pager']   = $this->libroModel->pager;
    
    // Pasar variables a la vista para mantener el estado de los filtros
    $data['perPage']             = $perPage;
    $data['buscar']              = $buscar;
    $data['coleccion_id_sel']    = $coleccion_id; // ID para el Select2
    $data['subgenero_id_sel']    = $subgenero_id; // ID para el Select2
    $data['cantidad_disponible'] = $cantidad_disponible;

    return view('Administrador/libros', $data);
}

    public function new()
    {
        return view('Administrador/Libros/nuevo');
    }

    /**
     * Create: Se eliminó el campo 'estado'. 
     * Ahora los ejemplares se crean siempre como 'Disponible' inicialmente.
     */
    public function create()
{
    // 1. Manejo de Subcategoría (Lógica de comodín/null)
    $subcategoriaId = $this->request->getPost('subcategoria_id');
    $subgeneroId    = $this->request->getPost('subgenero_id_dummy');

    if (empty($subcategoriaId) && !empty($subgeneroId)) {
        $comodin = $this->subcategoriaModel
            ->where('subgenero_id', $subgeneroId)
            ->groupStart()
                ->where('nombre', null)->orWhere('nombre', '')->orWhere('nombre', 'NULL')
            ->groupEnd()->first();
        $subcategoriaId = ($comodin) ? $comodin['subcategoria_id'] : null;
    }

    // 2. Preparación de datos del Libro
    $total       = (int)$this->request->getPost('cantidad_total');
    $disponibles = (int)$this->request->getPost('cantidad_disponibles');

    // Validación de seguridad: disponibles no puede ser mayor al total
    $disponiblesReal = min($disponibles, $total);

    $dataLibro = [
        'codigo'               => trim($this->request->getPost('codigo')),
        'titulo'               => trim($this->request->getPost('titulo')),
        'autor'                => trim($this->request->getPost('autor')),
        'editorial'            => trim($this->request->getPost('editorial')),
        'paginas'              => (int)$this->request->getPost('paginas'),
        'ano'                  => (int)$this->request->getPost('ano'),
        'subcategoria_id'      => $subcategoriaId,
        'cantidad_total'       => $total,
        'cantidad_disponibles' => $disponiblesReal,
    ];

    // 3. Inserción del Libro y creación de Ejemplares
    if ($this->libroModel->insert($dataLibro)) {
        $nuevoLibroId = $this->libroModel->getInsertID();
        
        if ($nuevoLibroId && $total > 0) {
            $ejemplares = [];
            
            // 🌟 LÓGICA DE ESTADOS:
            // Si total es 10 y disponibles 9, entonces 1 debe ser 'Dañado'.
            $cantidadDanados = $total - $disponiblesReal;

            for ($i = 0; $i < $total; $i++) {
                // Mientras el contador sea menor a la cantidad de dañados, asignamos ese estado
                $estado = ($i < $cantidadDanados) ? 'Dañado' : 'Disponible';

                $ejemplares[] = [
                    'libro_id' => $nuevoLibroId,
                    'estado'   => $estado, 
                    'no_copia' => $i + 1
                ];
            }
            
            // Inserción masiva para optimizar la base de datos
            $this->ejemplarModel->insertBatch($ejemplares);
        }

        return redirect()->to(base_url('libros'))->with('msg', 'Libro creado y ejemplares generados correctamente.');
    } else {
        return redirect()->back()->withInput()->with('errors', $this->libroModel->errors());
    }
}

    public function edit($id)
    {
        $libro = $this->libroModel
            ->select('libros.*, subcategorias.nombre as subcategoria_nombre')
            ->join('subcategorias', 'subcategorias.subcategoria_id = libros.subcategoria_id', 'left')
            ->find($id);

        if (!$libro) {
            return redirect()->to(base_url('libros'))->with('msg_error', 'Libro no encontrado.');
        }

        $data = ['libro' => $libro, 'coleccion_id' => null, 'subgenero_id' => null];

        if (!empty($libro['subcategoria_id'])) {
            $subcat = $this->subcategoriaModel->find($libro['subcategoria_id']);
            if ($subcat && !empty($subcat['subgenero_id'])) {
                $subgen = $this->subgeneroModel->find($subcat['subgenero_id']);
                if ($subgen) {
                    $data['subgenero_id'] = $subgen['subgenero_id'];
                    $data['subgenero_nombre'] = $subgen['nombre'];
                    if (!empty($subgen['coleccion_id'])) {
                        $col = $this->coleccionModel->find($subgen['coleccion_id']);
                        if ($col) {
                            $data['coleccion_id'] = $col['coleccion_id'];
                            $data['coleccion_nombre'] = $col['nombre'];
                        }
                    }
                }
            }
        }
        return view('Administrador/Libros/edit', $data);
    }

    /**
     * Update: Eliminado el campo 'estado' de la actualización.
     */
    public function update($id)
    {
        $subcategoriaId = $this->request->getPost('subcategoria_id');
        $subgeneroId    = $this->request->getPost('subgenero_id_dummy');

        $validationRules = [
            'titulo' => 'required|min_length[3]',
            'autor'  => 'required',
            'codigo' => "required|is_unique[libros.codigo,libro_id,$id]",
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (empty($subcategoriaId) && !empty($subgeneroId)) {
            $comodin = $this->subcategoriaModel
                ->where('subgenero_id', $subgeneroId)
                ->groupStart()
                    ->where('nombre', null)->orWhere('nombre', '')->orWhere('nombre', 'NULL')
                ->groupEnd()->first();
            $subcategoriaId = ($comodin) ? $comodin['subcategoria_id'] : null;
        }

        $dataUpdate = [
            'libro_id'             => $id, 
            'codigo'               => trim($this->request->getPost('codigo')),
            'titulo'               => trim($this->request->getPost('titulo')),
            'autor'                => trim($this->request->getPost('autor')),
            'editorial'            => trim($this->request->getPost('editorial')),
            'paginas'              => (int)$this->request->getPost('paginas'),
            'ano'                  => (int)$this->request->getPost('ano'),
            'subcategoria_id'      => $subcategoriaId,
            // 'estado' REMOVIDO
        ];

        if ($this->libroModel->update($id, $dataUpdate)) {
            return redirect()->to(base_url('libros'))->with('msg', 'Libro actualizado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->libroModel->errors());
        }
    }

    public function delete($id)
    {
        $libro = $this->libroModel->find($id);
        if (!$libro) {
            return redirect()->to(base_url('libros'))->with('msg_error', "El libro no existe");
        }

        $ejemplares = $this->ejemplarModel->where('libro_id', $id)->findAll();
        $ejemplar_ids = array_column($ejemplares, 'ejemplar_id');

        if (!empty($ejemplar_ids)) {
            $this->prestamoModel->whereIn('ejemplar_id', $ejemplar_ids)->delete();
        }
        
        $this->ejemplarModel->where('libro_id', $id)->delete();
        $this->libroModel->delete($id);

        return redirect()->to(base_url('libros'))->with('msg', 'Libro y sus ejemplares eliminados correctamente.');
    }

    // --- MÉTODOS AJAX (Select2) ---

    public function get_colecciones_json()
    {
        $term = $this->request->getGet('term'); $id = $this->request->getGet('id');
        $data = $id ? $this->coleccionModel->where('coleccion_id', $id)->findAll() : 
               ($term ? $this->coleccionModel->like('nombre', $term)->findAll(10) : $this->coleccionModel->findAll(10));

        return $this->response->setJSON(['results' => array_map(fn($i) => ['id' => $i['coleccion_id'], 'text' => $i['nombre']], $data)]);
    }

    public function get_subgeneros_json()
    {
        $coleccionId = $this->request->getGet('coleccion_id'); $term = $this->request->getGet('term'); $id = $this->request->getGet('id');
        $builder = $this->subgeneroModel;
        if ($id) $builder->where('subgenero_id', $id);
        else {
            if ($coleccionId) $builder->where('coleccion_id', $coleccionId);
            if ($term) $builder->like('nombre', $term);
        }
        $data = $builder->findAll(15);
        return $this->response->setJSON(['results' => array_map(fn($i) => ['id' => $i['subgenero_id'], 'text' => $i['nombre']], $data)]);
    }

    public function get_subcategorias_json()
    {
        $subgeneroId = $this->request->getGet('subgenero_id'); $term = $this->request->getGet('term'); $id = $this->request->getGet('id');
        $builder = $this->subcategoriaModel;
        if ($id) $builder->where('subcategoria_id', $id);
        else {
            if ($subgeneroId) $builder->where('subgenero_id', $subgeneroId);
            if ($term) $builder->like('nombre', $term);
        }
        $data = $builder->findAll(15);
        return $this->response->setJSON(['results' => array_map(fn($i) => ['id' => $i['subcategoria_id'], 'text' => $i['nombre']], $data)]);
    }
}