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
            // Guardamos la sesión con más datos
            session()->set([
                'usuario'   => $datosUsuario['carne'],  // si sigues usando carne
                'nombre'    => $datosUsuario['nombre'],
                'rol'       => $datosUsuario['rol'],    // 👈 importante
                'logged_in' => true
            ]);

            // Redirigir según rol
            switch ($datosUsuario['rol']) {
                case 'Administrador':
                    return redirect()->to('/administrador/panel');
                case 'Bibliotecario':
                    return redirect()->to('/bibliotecario/panel');
                case 'Alumno':
                case 'Maestro':
                    return redirect()->to('/alumno/panel');
                default:
                    return redirect()->to('/')->with('error', 'Rol no válido');
            }

        } else {
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos');
        }
    }

    // Esta función ya no servirá como "panel único",
    // pero la dejo por si quieres usarla como fallback
    public function panel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        // Se puede mandar al panel según rol:
        switch (session()->get('rol')) {
            case 'Administrador':
                return view('Administrador/panel');
            case 'Bibliotecario':
                return view('Bibliotecario/panel');
            case 'Alumno':
            case 'Maestro':
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