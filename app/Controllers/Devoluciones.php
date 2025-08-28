<?php

namespace App\Controllers;

use App\Models\PrestamoModel;
use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\EjemplarModel;

class Devoluciones extends BaseController
{
    /**
     * Mostrar lista de préstamos en proceso con opción de búsqueda
     */
    public function index()
    {
        $prestamoModel = new PrestamoModel();
        $usuarioModel  = new UsuarioModel();
        $libroModel    = new LibroModel();
        $ejemplarModel = new EjemplarModel();

        $buscar = $this->request->getGet('buscar');

        // Base query: solo prestamos en proceso
        $prestamos = $prestamoModel->where('estado', 'En proceso')->findAll();

        // Enriquecer los datos con carne, titulo y no_copia
        foreach ($prestamos as &$prestamo) {
            $usuario   = $usuarioModel->find($prestamo['usuario_id']);
            $libro     = $libroModel->find($prestamo['libro_id']);
            $ejemplar  = $ejemplarModel->find($prestamo['ejemplar_id']);

            $prestamo['carne']    = $usuario['carne'] ?? '';
            $prestamo['titulo']   = $libro['titulo'] ?? '';
            $prestamo['no_copia'] = $ejemplar['no_copia'] ?? '';
        }

        // Filtrar si hay búsqueda
        if (!empty($buscar)) {
            $prestamos = array_filter($prestamos, function ($p) use ($buscar) {
                return stripos($p['carne'], $buscar) !== false
                    || stripos($p['titulo'], $buscar) !== false
                    || stripos($p['no_copia'], $buscar) !== false;
            });
        }

        $data = [
            'prestamos' => $prestamos,
            'buscar'    => $buscar
        ];

        return view('Bibliotecario/Gestion/devoluciones', $data);
    }

    /**
     * Guardar devolución
     */
    public function store()
    {
        $prestamoModel = new PrestamoModel();
        $ejemplarModel = new EjemplarModel();

        $prestamo_id   = $this->request->getPost('prestamo_id');
        $fecha_devuelto = $this->request->getPost('fecha_devuelto');

        // Obtener préstamo
        $prestamo = $prestamoModel->find($prestamo_id);

        if (!$prestamo) {
            return redirect()->back()->with('msg', 'Préstamo no encontrado');
        }

        // Actualizar préstamo
        $prestamoModel->update($prestamo_id, [
            'fecha_devuelto' => $fecha_devuelto,
            'estado' => 'Devuelto'
        ]);

        // Cambiar ejemplar a disponible
        $ejemplarModel->update($prestamo['ejemplar_id'], [
            'estado' => 'Disponible'
        ]);

        return redirect()->to(base_url('devoluciones'))
                         ->with('msg', 'Devolución registrada correctamente');
    }
}
