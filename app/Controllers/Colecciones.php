<?php
namespace App\Controllers;

use App\Models\ColeccionModel;
use App\Models\SubgeneroModel;
use App\Models\SubcategoriaModel;
use CodeIgniter\Controller;

class Colecciones extends Controller
{
    protected $coleccionModel;

    public function __construct()
    {
        $this->coleccionModel = new ColeccionModel();
        helper(['form', 'url']);
    }

    /**
     * Lista las colecciones con su jerarquía (Subgénero y Subcategoría)
     * Incluye Paginación, Búsqueda y Ordenamiento
     */
    public function index()
    {
        // 1. Configuración de paginación similar a Libros.php
        $defaultPerPage = 10;
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 
        if ($perPage < 1) { $perPage = $defaultPerPage; }

        $buscar = $this->request->getGet('buscar');
        $ordenar = $this->request->getGet('ordenar');

        // 2. Construcción de la consulta con JOINs
        // Importante: No usamos ->builder(), usamos el modelo directamente para que funcione el paginate()
        $builder = $this->coleccionModel
            ->select('colecciones.coleccion_id, colecciones.nombre as coleccion, 
                      subgeneros.nombre as subgenero, 
                      subcategorias.nombre as subcategoria')
            ->join('subgeneros', 'subgeneros.coleccion_id = colecciones.coleccion_id', 'left')
            ->join('subcategorias', 'subcategorias.subgenero_id = subgeneros.subgenero_id', 'left');

        // 3. Aplicar filtros de búsqueda
        if ($buscar) {
            $builder = $builder->groupStart()
                    ->like('colecciones.nombre', $buscar)
                    ->orLike('subgeneros.nombre', $buscar)
                    ->orLike('subcategorias.nombre', $buscar)
                    ->groupEnd();
        }

        // 4. Lógica de ordenación
        if ($ordenar) {
            switch ($ordenar) {
                case 'nombre_asc':
                    $builder->orderBy('colecciones.nombre', 'ASC');
                    break;
                case 'nombre_desc':
                    $builder->orderBy('colecciones.nombre', 'DESC');
                    break;
                case 'reciente':
                    $builder->orderBy('colecciones.coleccion_id', 'DESC');
                    break;
                case 'viejo':
                    $builder->orderBy('colecciones.coleccion_id', 'ASC');
                    break;
                default:
                    $builder->orderBy('colecciones.coleccion_id', 'DESC');
            }
        } else {
            $builder->orderBy('colecciones.coleccion_id', 'DESC');
        }

        // 5. Preparar datos para la vista con paginate()
        $data = [
            'colecciones' => $builder->paginate($perPage, 'default'),
            'pager'       => $this->coleccionModel->pager,
            'perPage'     => $perPage,
            'buscar'      => $buscar,
            'ordenar'     => $ordenar
        ];

        return view('Administrador/colecciones', $data);
    }

    /**
     * Formulario para nueva colección
     */
    public function create()
    {
        return view('Administrador/Colecciones/nuevo');
    }

    /**
     * Guardar nueva colección
     */
    public function store()
    {
        $nombre = $this->request->getPost('nombre');
        $this->coleccionModel->insert(['nombre' => $nombre]);

        return redirect()->to(base_url('colecciones'))->with('msg', 'Colección creada con éxito');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $coleccion = $this->coleccionModel->find($id);

        if (!$coleccion) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Colección no encontrada');
        }

        return view('Administrador/Colecciones/edit', [
            'coleccion' => $coleccion
        ]);
    }

    /**
     * Actualizar registro
     */
    public function update($id)
    {
        $nombre = $this->request->getPost('nombre');
        $this->coleccionModel->update($id, ['nombre' => $nombre]);

        return redirect()->to(base_url('colecciones'))->with('msg', 'Colección actualizada correctamente');
    }

    /**
     * Eliminar registro
     */
    public function delete($id)
    {
        // Nota: Debido a tus restricciones SQL (ON DELETE CASCADE), 
        // eliminar una colección eliminará automáticamente sus subgéneros y subcategorías.
        $this->coleccionModel->delete($id);
        
        return redirect()->to(base_url('colecciones'))->with('msg', 'Colección eliminada correctamente');
    }
}