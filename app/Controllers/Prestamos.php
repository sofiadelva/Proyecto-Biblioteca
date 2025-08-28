<?php

namespace App\Controllers;

use App\Models\PrestamoModel;
use App\Models\LibroModel;
use App\Models\EjemplarModel;
use App\Models\UsuarioModel;

class Prestamos extends BaseController
{
    /**
     * Mostrar formulario de creación de préstamo
     */
    public function create()
    {
        $libroModel = new LibroModel();

        // Traer lista de libros para el select
        $data['libros'] = $libroModel->findAll();

        return view('Bibliotecario/Gestion/prestamos', $data);
    }

    /**
     * Obtener ejemplares disponibles de un libro (AJAX)
     */
    public function getEjemplares($libro_id)
    {
        $ejemplarModel = new EjemplarModel();

        // Obtener ejemplares disponibles de ese libro
        $ejemplares = $ejemplarModel
            ->where('libro_id', $libro_id)
            ->where('estado', 'Disponible')
            ->findAll();

        return $this->response->setJSON($ejemplares);
    }

    /**
     * Guardar préstamo en BD
     */
    public function store()
    {
        $prestamoModel = new PrestamoModel();
        $usuarioModel  = new UsuarioModel();
        $ejemplarModel = new EjemplarModel();

        // Validar si el usuario existe mediante su carné
        $carne   = $this->request->getPost('carne');
        $usuario = $usuarioModel->where('carne', $carne)->first();

        if (!$usuario) {
            return redirect()->back()->with('msg', 'El usuario no existe');
        }

        // Guardar préstamo
        $prestamoModel->insert([
            'libro_id'            => $this->request->getPost('libro_id'),
            'ejemplar_id'         => $this->request->getPost('ejemplar_id'),
            'usuario_id'          => $usuario['usuario_id'],
            'fecha_prestamo'      => $this->request->getPost('fecha_prestamo'),
            'fecha_de_devolucion' => $this->request->getPost('fecha_de_devolucion'),
            'estado'              => 'En proceso'
        ]);

        // Cambiar estado del ejemplar a "No disponible"
        $ejemplarModel->update(
            $this->request->getPost('ejemplar_id'),
            ['estado' => 'No disponible']
        );

        return redirect()->to(base_url('prestamos'))
                         ->with('msg', 'Préstamo agregado correctamente');
    }
}
