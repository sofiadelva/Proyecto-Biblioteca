<?php
namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class Categorias extends Controller
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
        helper(['form', 'url']);
    }

    // Lista las categorías con paginación, filtros y ordenación
    public function index()
    {
        $defaultPerPage = 10;
        
        // Obtener parámetros GET
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        
        // Obtener y validar $perPage (filas por página)
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        // Aseguramos que $perPage sea al menos 1 para evitar la División por Cero.
        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        // Crear el constructor de consultas
        $builder = $this->categoriaModel;

        // Aplicar la búsqueda por nombre de categoría si existe un término
        if ($buscar) {
            $builder = $builder->like('nombre', $buscar, 'both');
        }

        // Aplicar ordenación
        if ($ordenar) {
            switch ($ordenar) {
                case 'nombre_asc':
                    $builder = $builder->orderBy('nombre', 'ASC');
                    break;
                case 'nombre_desc':
                    $builder = $builder->orderBy('nombre', 'DESC');
                    break;
                case 'reciente':
                    $builder = $builder->orderBy('categoria_id', 'DESC'); // más reciente primero
                    break;
                case 'viejo':
                    $builder = $builder->orderBy('categoria_id', 'ASC'); // más viejo primero
                    break;
            }
        }
        
        // Aplicar paginación
        $data['categorias'] = $builder->paginate($perPage, 'default');
        
        // Pasar datos a la vista
        $data['pager'] = $this->categoriaModel->pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar; // Pasar el término de búsqueda para mantenerlo visible

        // Cambiar la ruta de la vista
        return view('Administrador/categorias', $data);
    }

    // Formulario para crear nueva categoría
    public function create()
    {
        // Cambiar la ruta de la vista
        return view('Administrador/Categorías/nuevo');
    }

    // Guardar nueva categoría
    public function store()
    {
        $nombre = $this->request->getPost('nombre');

        $this->categoriaModel->insert(['nombre' => $nombre]);

        return redirect()->to(base_url('categorias'))->with('msg', 'Categoría creada correctamente');
    }

    // Formulario para editar categoría
    public function edit($id)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Categoría no encontrada');
        }

        // Cambiar la ruta de la vista
        return view('Administrador/Categorías/edit', [
            'categoria' => $categoria
        ]);
    }

    // Actualizar categoría
    public function update($id)
    {
        $nombre = $this->request->getPost('nombre');

        $this->categoriaModel->update($id, ['nombre' => $nombre]);

        return redirect()->to(base_url('categorias'))->with('msg', 'Categoría actualizada correctamente');
    }

    // Eliminar categoría
    public function delete($id)
    {
        $this->categoriaModel->delete($id);
        return redirect()->to(base_url('categorias'))->with('msg', 'Categoría eliminada correctamente');
    }
}