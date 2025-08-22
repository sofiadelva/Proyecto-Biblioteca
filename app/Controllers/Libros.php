<?php
namespace App\Controllers;

use App\Models\LibroModel;
use CodeIgniter\Controller;

class Libros extends Controller
{
    protected $libroModel;

    public function __construct()
    {
        $this->libroModel = new LibroModel();
    }

    // LISTAR
    public function index()
    {
        $data['libros'] = $this->libroModel->findAll();
        return view('Administrador/libros', $data);
    }

    // FORM NUEVO
    public function new()
    {
        return view('Administrador/Libros/nuevo');
    }

    // GUARDAR
    public function create()
    {
        $this->libroModel->insert([
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
        ]);
        return redirect()->to(base_url('libros'));
    }

    // EDITAR
    public function edit($id)
    {
        $data['libro'] = $this->libroModel->find($id);
        return view('Administrador/Libros/edit', $data);
    }

    // ACTUALIZAR
    public function update($id)
    {
        $this->libroModel->update($id, [
            'titulo' => $this->request->getPost('titulo'),
            'autor'  => $this->request->getPost('autor'),
            'editorial' => $this->request->getPost('editorial'),
            'cantidad_total' => $this->request->getPost('cantidad_total'),
            'cantidad_disponibles' => $this->request->getPost('cantidad_disponibles'),
            'estado' => $this->request->getPost('estado'),
        ]);
        return redirect()->to(base_url('libros'));
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->libroModel->delete($id);
        return redirect()->to(base_url('libros'));
    }
}