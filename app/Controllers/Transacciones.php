<?php

namespace App\Controllers;

use App\Models\TransaccionModel;
use App\Models\UsuarioModel; 
use App\Models\EjemplarModel; 
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

    /**
     * Muestra la tabla de listado de transacciones (préstamos) con paginación, filtros y ordenación.
     */
    public function index()
    {
        $defaultPerPage = 10;
        
        // Obtener parámetros GET y validarlos
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        $estadoFiltro = $this->request->getGet('estado');
        
        // Obtener y validar $perPage (filas por página)
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        // --- Configuración del Constructor de Consultas ---
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
             $builder = $builder->orderBy('p.prestamo_id', 'DESC');
        }

        // --- Implementación de Paginación Manual ---
        $page = $this->request->getGet('page') ?? 1;
        $page = (int)$page;

        $total = $builder->countAllResults(false); 

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'bootstrap_full'); 

        $offset = ($page - 1) * $perPage;
        $builder->limit($perPage, $offset);

        $data['transacciones'] = $builder->get()->getResultArray();
        
        // --- Pasar datos a la vista ---
        $data['pager'] = $pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar;
        $data['estadoFiltro'] = $estadoFiltro; 

        return view('Administrador/transacciones', $data);
    }

    /**
     * Muestra el formulario para registrar un nuevo préstamo.
     */
    public function create()
    {
        return view('Administrador/Transacciones/nuevo');
    }

    /**
     * Guarda un nuevo préstamo (transacción) en la base de datos.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $prestamoModel = $this->transaccionModel;
        $usuarioModel  = new UsuarioModel();
        $ejemplarModel = new EjemplarModel();

        $carne = $this->request->getPost('carne');
        $fecha_prestamo = $this->request->getPost('fecha_prestamo');
        $fecha_devolucion = $this->request->getPost('fecha_de_devolucion');
        
        $usuario = $usuarioModel->where('carne', $carne)->first();

        // 1. Validación clave del usuario
        $validation = \Config\Services::validation();
        if (!$usuario) {
            $validation->setError('carne', 'El usuario con carné ' . esc($carne) . ' no existe.');
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // 2. Validación de campos obligatorios
        $rules = [
            'carne'                 => 'required',
            'libro_id'              => 'required|is_natural_no_zero',
            'ejemplar_id'           => 'required|is_natural_no_zero',
            'fecha_prestamo'        => 'required|valid_date',
            'fecha_de_devolucion'   => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // 3. Validación manual de fechas (Fecha Devolución debe ser > Fecha Préstamo)
        try {
            $fechaPrestamoObj = new \DateTime($fecha_prestamo);
            $fechaDevolucionObj = new \DateTime($fecha_devolucion);

            if ($fechaDevolucionObj <= $fechaPrestamoObj) {
                $validation->setError('fecha_de_devolucion', 'La fecha límite de devolución debe ser posterior a la fecha de préstamo.');
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        } catch (\Exception $e) {
            $validation->setError('fecha_de_devolucion', 'Formato de fecha inválido.');
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // 4. Guardar el préstamo (transacción)
        $data = [
            'libro_id'              => $this->request->getPost('libro_id'),
            'ejemplar_id'           => $this->request->getPost('ejemplar_id'),
            'usuario_id'            => $usuario['usuario_id'], 
            'fecha_prestamo'        => $fecha_prestamo,
            'fecha_de_devolucion'   => $fecha_devolucion,
            'fecha_devuelto'        => null, 
            'estado'                => 'En proceso' // Estado inicial
        ];

        if ($prestamoModel->insert($data)) {
            // Cambiar estado del ejemplar a "No disponible"
            $ejemplarModel->update(
                $this->request->getPost('ejemplar_id'),
                ['estado' => 'No disponible']
            );
            return redirect()->to(base_url('transacciones'))->with('msg', 'Préstamo (Transacción) agregado correctamente.');
        } else {
             return redirect()->back()->withInput()->with('msg', 'Error al guardar el préstamo en la base de datos.');
        }
    }

    /**
     * Muestra el formulario para editar una transacción existente.
     */
    public function edit($id)
    {
        $data['transaccion'] = $this->transaccionModel->find($id);
        
        return view('Administrador/Transacciones/edit', $data);
    }

    /**
     * Actualiza una transacción existente en la base de datos.
     */
    public function update($id)
    {
        $ejemplarModel = new EjemplarModel();
        
        $oldTransaccion = $this->transaccionModel->find($id);

        $data = [
            'libro_id' => $this->request->getPost('libro_id'),
            'ejemplar_id' => $this->request->getPost('ejemplar_id'),
            'usuario_id' => $this->request->getPost('usuario_id'),
            'fecha_prestamo' => $this->request->getPost('fecha_prestamo'),
            'fecha_de_devolucion' => $this->request->getPost('fecha_de_devolucion'),
            'fecha_devuelto' => $this->request->getPost('fecha_devuelto') ?? null, 
            'estado' => $this->request->getPost('estado') // Solo 'En proceso' o 'Devuelto'
        ];

        // 1. Control del ejemplar si el ID cambia
        if ($oldTransaccion['ejemplar_id'] != $data['ejemplar_id']) {
            // Liberar el viejo ejemplar
            if ($oldTransaccion['ejemplar_id']) {
                $ejemplarModel->update($oldTransaccion['ejemplar_id'], ['estado' => 'Disponible']);
            }
            // Ocupar el nuevo ejemplar (si la transacción está activa)
            if ($data['estado'] === 'En proceso') {
                 $ejemplarModel->update($data['ejemplar_id'], ['estado' => 'No disponible']);
            }
        }
        
        // 2. Control del ejemplar basado en el cambio de estado de la transacción
        if ($data['estado'] === 'Devuelto' && $oldTransaccion['estado'] !== 'Devuelto') {
            // Si se marca como Devuelto, el ejemplar se marca como Disponible
            $ejemplarModel->update($data['ejemplar_id'], ['estado' => 'Disponible']);
        } elseif ($data['estado'] === 'En proceso' && $oldTransaccion['estado'] === 'Devuelto') {
            // Si estaba devuelto y ahora vuelve a estar activo, el ejemplar se marca No disponible
             $ejemplarModel->update($data['ejemplar_id'], ['estado' => 'No disponible']);
        }


        // 3. Guardar la actualización de la transacción
        if ($this->transaccionModel->update($id, $data)) {
            session()->setFlashdata('msg', 'Transacción actualizada correctamente.');
        } else {
            session()->setFlashdata('msg', 'Error al actualizar la transacción.');
        }
        
        return redirect()->to('/transacciones');
    }

    /**
     * Elimina una transacción (préstamo) de la base de datos.
     */
    public function delete($id)
    {
        $ejemplarModel = new EjemplarModel();
        $transaccion = $this->transaccionModel->find($id);
        
        // 1. Liberar el ejemplar si el préstamo estaba activo ('En proceso')
        if ($transaccion && $transaccion['estado'] === 'En proceso') {
            $ejemplarModel->update($transaccion['ejemplar_id'], ['estado' => 'Disponible']);
        }
        
        // 2. Eliminar la transacción
        $this->transaccionModel->delete($id);
        
        session()->setFlashdata('msg', 'Transacción eliminada correctamente. El ejemplar ha sido liberado.');
        return redirect()->to('/transacciones');
    }
}