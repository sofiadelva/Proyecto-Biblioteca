<?php

namespace App\Controllers;

use App\Models\TransaccionModel;
use App\Models\UsuarioModel; 
use App\Models\EjemplarModel; 
use App\Models\LibroModel; // Añadido para consistencia
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
     * Listado histórico de transacciones
     */
    public function index()
    {
        $defaultPerPage = 10;
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        $estadoFiltro = $this->request->getGet('estado');
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        if ($perPage < 1) $perPage = $defaultPerPage; 

        $builder = $this->db->table('prestamos p');
        $builder->select('p.*, l.titulo, l.codigo, e.no_copia, u.nombre as usuario_nombre, u.carne as carne');
        $builder->join('libros l', 'l.libro_id = p.libro_id');
        $builder->join('ejemplares e', 'e.ejemplar_id = p.ejemplar_id');
        $builder->join('usuarios u', 'u.usuario_id = p.usuario_id');

        if ($buscar) {
            $builder->groupStart()
                    ->like('l.titulo', $buscar, 'both')
                    ->orLike('u.nombre', $buscar, 'both')
                    ->orLike('l.codigo', $buscar, 'both')
                    ->orLike('u.carne', $buscar, 'both')
                    ->groupEnd();
        }

        if ($estadoFiltro) {
            $builder->where('p.estado', $estadoFiltro);
        }

        // Ordenación
        switch ($ordenar) {
            case 'fecha_asc':   $builder->orderBy('p.fecha_prestamo', 'ASC'); break;
            case 'fecha_desc':  $builder->orderBy('p.fecha_prestamo', 'DESC'); break;
            case 'titulo_asc':  $builder->orderBy('l.titulo', 'ASC'); break;
            case 'usuario_asc': $builder->orderBy('u.nombre', 'ASC'); break;
            default:            $builder->orderBy('p.prestamo_id', 'DESC'); break;
        }

        $page = (int)($this->request->getGet('page') ?? 1);
        $total = $builder->countAllResults(false); 

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'bootstrap_full'); 

        $offset = ($page - 1) * $perPage;
        $builder->limit($perPage, $offset);

        $data = [
            'transacciones' => $builder->get()->getResultArray(),
            'pager'         => $pager,
            'perPage'       => $perPage,
            'buscar'        => $buscar,
            'estadoFiltro'  => $estadoFiltro,
            'ordenar'       => $ordenar // <--- AGREGA ESTA LÍNEA
        ];

        return view('Administrador/transacciones', $data);
    }

    /**
     * Solo para editar datos erróneos de una transacción existente
     */
    public function edit($id)
    {
        $builder = $this->db->table('prestamos p');
        $builder->select('p.*, l.titulo, l.codigo, e.no_copia, u.nombre as usuario_nombre');
        $builder->join('libros l', 'l.libro_id = p.libro_id');
        $builder->join('ejemplares e', 'e.ejemplar_id = p.ejemplar_id');
        $builder->join('usuarios u', 'u.usuario_id = p.usuario_id');
        $builder->where('p.prestamo_id', $id);
        
        $data['transaccion'] = $builder->get()->getRowArray();

        if (!$data['transaccion']) return redirect()->to('/transacciones')->with('msg', 'No existe.');
        
        return view('Administrador/Transacciones/edit', $data);
    }

   public function update($id)
    {
        // 1. Buscamos la transacción actual
        $transaccionActual = $this->transaccionModel->find($id);
        if (!$transaccionActual) {
            return redirect()->back()->with('msg', 'Registro no encontrado.');
        }

        $data = $this->request->getPost();
        $usuarioEnviado = $this->request->getPost('usuario_id'); // Aquí viene el carné (20200150)

        // 2. TRADUCCIÓN: Carné -> usuario_id
        // Solo buscamos si el carné enviado es distinto al que ya tenía (para ahorrar recursos)
        // O si simplemente queremos asegurar que siempre sea el ID correcto.
        
        $usuarioModel = new \App\Models\UsuarioModel();
        $usuarioRow = $usuarioModel->where('carne', $usuarioEnviado)->first();

        if ($usuarioRow) {
            // Si encontramos al usuario por su carné, usamos su ID real para la DB
            $data['usuario_id'] = $usuarioRow['usuario_id'];
        } else {
            // Si por alguna razón no lo encuentra, dejamos el que ya tenía para que no truene
            $data['usuario_id'] = $transaccionActual['usuario_id'];
        }

        // 3. Lógica de limpieza y stock (la que ya teníamos)
        $estadoAnterior = trim($transaccionActual['estado']);
        $nuevoEstado    = trim($data['estado']);

        if ($estadoAnterior === 'Devuelto' && $nuevoEstado === 'En proceso') {
            $this->db->transStart();
            $this->db->table('ejemplares')
                    ->where('ejemplar_id', $transaccionActual['ejemplar_id'])
                    ->update(['estado' => 'Prestado']); 
            
            $this->db->table('libros')
                    ->where('libro_id', $transaccionActual['libro_id'])
                    ->set('cantidad_disponibles', 'cantidad_disponibles - 1', false)
                    ->update();
            $this->db->transComplete();
            $data['fecha_devuelto'] = null;
        }

        if ($nuevoEstado === 'En proceso') {
            $data['fecha_devuelto'] = null;
        }

        // 4. Guardar cambios
        if ($this->db->table('prestamos')->where('prestamo_id', $id)->update($data)) {
            return redirect()->to('/transacciones')->with('msg', 'Actualizado correctamente.');
        }

        return redirect()->back()->with('msg', 'Error al procesar la actualización.');
    }

    public function delete($id)
    {
        // 1. Buscamos la transacción ANTES de eliminarla para obtener los IDs
        $transaccion = $this->transaccionModel->find($id);

        if (!$transaccion) {
            return redirect()->to('/transacciones')->with('msg', 'El registro ya no existe.');
        }

        // 2. Iniciamos transacción de DB para que todo sea atómico
        $this->db->transStart();

        // 3. SOLO si la transacción estaba "En proceso", devolvemos el stock.
        // (Si ya estaba 'Devuelto', el stock ya se sumó en su momento, así que no hacemos nada)
        if (trim($transaccion['estado']) === 'En proceso') {
            
            // A. Cambiar ejemplar a 'Disponible'
            $this->db->table('ejemplares')
                ->where('ejemplar_id', $transaccion['ejemplar_id'])
                ->update(['estado' => 'Disponible']);

            // B. Sumar 1 a cantidad_disponibles
            $this->db->table('libros')
                ->where('libro_id', $transaccion['libro_id'])
                ->set('cantidad_disponibles', 'cantidad_disponibles + 1', false)
                ->update();
        }

        // 4. Ahora sí, eliminamos el préstamo
        $this->transaccionModel->delete($id);

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->to('/transacciones')->with('msg', 'Error al intentar eliminar y actualizar stock.');
        }

        return redirect()->to('/transacciones')->with('msg', 'Registro eliminado. El inventario se ha ajustado correctamente.');
    }
}