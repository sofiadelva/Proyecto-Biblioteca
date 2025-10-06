<?php

namespace App\Controllers;

use App\Models\TransaccionModel;
use CodeIgniter\Controller;

class Transacciones extends BaseController
{
    protected $transaccionModel;
    protected $db;

    public function __construct()
    {
        $this->transaccionModel = new TransaccionModel();
        $this->db = \Config\Database::connect();
    }

    // Listado con paginación, filtros y ordenación
    public function index()
    {
        $defaultPerPage = 10;
        
        // Obtener parámetros GET y validarlos
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        $estadoFiltro = $this->request->getGet('estado');
        
        // Obtener y validar $perPage (filas por página)
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        // Aseguramos que $perPage sea al menos 1 para evitar la División por Cero.
        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        // --- Configuración del Constructor de Consultas ---
        
        // Inicializar el Builder
        $builder = $this->db->table('prestamos p');
        $builder->select('p.*, l.titulo, e.no_copia, u.nombre as usuario_nombre');
        $builder->join('libros l', 'l.libro_id = p.libro_id');
        $builder->join('ejemplares e', 'e.ejemplar_id = p.ejemplar_id');
        $builder->join('usuarios u', 'u.usuario_id = p.usuario_id');

        // Aplicar BÚSQUEDA por título o usuario
        if ($buscar) {
            $builder = $builder->groupStart()
                               ->like('l.titulo', $buscar, 'both')
                               ->orLike('u.nombre', $buscar, 'both')
                               ->groupEnd();
        }

        // Aplicar FILTRO por estado
        if ($estadoFiltro) {
            $builder = $builder->where('p.estado', $estadoFiltro);
        }

        // Aplicar ORDENACIÓN
        if ($ordenar) {
            switch ($ordenar) {
                case 'fecha_asc':
                    $builder = $builder->orderBy('p.fecha_prestamo', 'ASC');
                    break;
                case 'fecha_desc':
                    $builder = $builder->orderBy('p.fecha_prestamo', 'DESC');
                    break;
                case 'titulo_asc':
                    $builder = $builder->orderBy('l.titulo', 'ASC');
                    break;
                case 'usuario_asc':
                    $builder = $builder->orderBy('u.nombre', 'ASC');
                    break;
            }
        } else {
             // Orden por defecto: más reciente primero
             $builder = $builder->orderBy('p.prestamo_id', 'DESC');
        }

        // --- Implementación de Paginación Manual ---
        
        // Obtener la página actual de la URL
        $page = $this->request->getGet('page') ?? 1;
        $page = (int)$page;

        // Contar el total de registros ANTES de aplicar limit/offset
        $total = $builder->countAllResults(false); // countAllResults(false) mantiene la consulta intacta

        // Configurar la paginación
        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'bootstrap_full'); // Usa el mismo template que en la vista

        // Aplicar limit y offset al Builder
        $offset = ($page - 1) * $perPage;
        $builder->limit($perPage, $offset);

        // Obtener los resultados paginados
        $data['transacciones'] = $builder->get()->getResultArray();
        
        // --- Pasar datos a la vista ---
        $data['pager'] = $pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar;
        $data['estadoFiltro'] = $estadoFiltro; // Para mantener el filtro seleccionado en la vista

        return view('Administrador/transacciones', $data);
    }

    // Formulario creación
    public function create()
    {
        $data['libros'] = $this->db->table('libros')->get()->getResultArray();
        $data['ejemplares'] = $this->db->table('ejemplares')->get()->getResultArray();
        $data['usuarios'] = $this->db->table('usuarios')->get()->getResultArray();

        return view('Administrador/Transacciones/nuevo', $data);
    }

    // Guardar
    public function store()
    {
        $this->transaccionModel->save([
            'libro_id' => $this->request->getPost('libro_id'),
            'ejemplar_id' => $this->request->getPost('ejemplar_id'),
            'usuario_id' => $this->request->getPost('usuario_id'),
            'fecha_prestamo' => $this->request->getPost('fecha_prestamo'),
            'fecha_de_devolucion' => $this->request->getPost('fecha_de_devolucion'),
            'fecha_devuelto' => $this->request->getPost('fecha_devuelto'),
            'estado' => $this->request->getPost('estado')
        ]);

        session()->setFlashdata('msg', 'Transacción registrada correctamente.');
        return redirect()->to('/transacciones');
    }

    // Formulario edición
    public function edit($id)
    {
        $data['transaccion'] = $this->transaccionModel->find($id);
        $data['libros'] = $this->db->table('libros')->get()->getResultArray();
        $data['ejemplares'] = $this->db->table('ejemplares')->get()->getResultArray();
        $data['usuarios'] = $this->db->table('usuarios')->get()->getResultArray();

        return view('Administrador/Transacciones/edit', $data);
    }

    // Actualizar
    public function update($id)
    {
        $this->transaccionModel->update($id, [
            'libro_id' => $this->request->getPost('libro_id'),
            'ejemplar_id' => $this->request->getPost('ejemplar_id'),
            'usuario_id' => $this->request->getPost('usuario_id'),
            'fecha_prestamo' => $this->request->getPost('fecha_prestamo'),
            'fecha_de_devolucion' => $this->request->getPost('fecha_de_devolucion'),
            'fecha_devuelto' => $this->request->getPost('fecha_devuelto'),
            'estado' => $this->request->getPost('estado')
        ]);

        session()->setFlashdata('msg', 'Transacción actualizada correctamente.');
        return redirect()->to('/transacciones');
    }

    // Eliminar
    public function delete($id)
    {
        $this->transaccionModel->delete($id);
        session()->setFlashdata('msg', 'Transacción eliminada correctamente.');
        return redirect()->to('/transacciones');
    }
}
