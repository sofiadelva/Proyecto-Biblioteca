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

    // Lista las categorías
    public function index()
    {
        $categorias = $this->categoriaModel->findAll();

        // Cambiar la ruta de la vista
        return view('Administrador/categorias', [
            'categorias' => $categorias
        ]);
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
