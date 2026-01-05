<?php
namespace App\Controllers;
use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
{
    $usuario = $this->request->getPost('usuario');
    $password = $this->request->getPost('password');

    $usuarioModel = new UsuarioModel();
    $datosUsuario = $usuarioModel->verificarUsuario($usuario, $password);

    if ($datosUsuario) {
        session()->set([
            'usuario_id' => $datosUsuario['usuario_id'],
            'usuario'    => $datosUsuario['carne'],      
            'nombre'     => $datosUsuario['nombre'],
            'rol'        => $datosUsuario['rol'],
            'logged_in'  => true
        ]);

        // Redirigir según rol
        switch ($datosUsuario['rol']) {
            case 'Administrador':
                return redirect()->to('/administrador/panel');
            // Eliminamos Bibliotecario si ya no lo usas, o lo dejamos por si acaso
            case 'Bibliotecario':
                return redirect()->to('/bibliotecario/panel');
            // UNIFICACIÓN: Ambos roles van al mismo panel
            case 'Alumno':
            case 'Docente': // <--- Cambiado de 'Maestro' a 'Docente'
                return redirect()->to('/alumno/panel');
            default:
                return redirect()->to('/')->with('error', 'Rol no válido en el sistema');
        }
    } else {
        return redirect()->back()->with('error', 'Usuario o contraseña incorrectos');
    }
}

public function panel()
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/');
    }

    switch (session()->get('rol')) {
        case 'Administrador':
            return view('Administrador/panel');
        case 'Bibliotecario':
            return view('Bibliotecario/panel');
        case 'Alumno':
        case 'Docente': // <--- Cambiado de 'Maestro' a 'Docente'
            return view('Alumno/panel');
        default:
            return redirect()->to('/')->with('error', 'No tienes permiso.');
    }
}

    public function salir()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    
}