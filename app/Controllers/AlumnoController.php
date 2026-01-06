<?php

namespace App\Controllers;

use App\Models\UsuarioModel; // Asegúrate de tener este modelo
use App\Controllers\BaseController;

class AlumnoController extends BaseController
{
    public function cambiarPassword()
    {
        return view('Alumno/cambiar_password');
    }

    public function updatePassword()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();
        $usuarioId = $session->get('usuario_id');

        $nueva_pass = $this->request->getPost('pass_nueva');
        $confirm_pass = $this->request->getPost('pass_confirm');

        if ($nueva_pass !== $confirm_pass) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
        }

        if (strlen($nueva_pass) < 4) {
            return redirect()->back()->with('error', 'La contraseña es muy corta.');
        }

        // Guardar en formato MD5
        $usuarioModel->update($usuarioId, [
            'password' => md5($nueva_pass)
        ]);

        return redirect()->to(base_url('alumno/password'))->with('success', 'Contraseña actualizada correctamente.');
    }
}