<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Usuarios extends Controller
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    // Mostrar todos los usuarios
    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        echo view('Administrador/usuarios', $data);
    }

    // Mostrar formulario para agregar
    public function create()
    {
        echo view('Administrador/Usuarios/nuevo');
    }

    // Guardar nuevo usuario
    public function store()
    {
        $data = $this->request->getPost();
        $data['password'] = md5($data['password']); // cifrar contraseña

        $this->usuarioModel->insert($data);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario agregado correctamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $data['usuario'] = $this->usuarioModel->find($id);
        echo view('Administrador/Usuarios/edit', $data);
    }

    // Actualizar usuario
    public function update($id)
    {
        $data = $this->request->getPost();

        if (!empty($data['password'])) {
            $data['password'] = md5($data['password']); // si cambió la contraseña, cifrar
        } else {
            unset($data['password']); // si no, mantener la anterior
        }

        $this->usuarioModel->update($id, $data);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function delete($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario eliminado correctamente.');
    }
}
