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

    // Listado
    public function index()
    {
        $builder = $this->db->table('prestamos p');
        $builder->select('p.*, l.titulo, e.no_copia, u.nombre as usuario_nombre');
        $builder->join('libros l', 'l.libro_id = p.libro_id');
        $builder->join('ejemplares e', 'e.ejemplar_id = p.ejemplar_id');
        $builder->join('usuarios u', 'u.usuario_id = p.usuario_id');

        $data['transacciones'] = $builder->get()->getResultArray();

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
