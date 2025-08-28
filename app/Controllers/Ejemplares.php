<?php

namespace App\Controllers;

use App\Models\EjemplarModel;
use App\Models\LibroModel;

class Ejemplares extends BaseController
{
    protected $ejemplarModel;
    protected $libroModel;

    public function __construct()
    {
        $this->ejemplarModel = new EjemplarModel();
        $this->libroModel = new LibroModel();
    }

    // 📌 Listar ejemplares de un libro
    public function listar($libro_id)
{
    $libro = $this->libroModel->find($libro_id);

    $ejemplares = $this->ejemplarModel
        ->select('ejemplares.*, libros.titulo as titulo_libro')
        ->join('libros', 'libros.libro_id = ejemplares.libro_id')
        ->where('ejemplares.libro_id', $libro_id)
        ->findAll();

    return view('Administrador/Libros/Ejemplares/listar', [
        'libro' => $libro,
        'ejemplares' => $ejemplares
    ]);
}


    // 📌 Formulario nuevo ejemplar
    public function new($libro_id)
    {
        $libro = $this->libroModel->find($libro_id);

        return view('Administrador/Libros/Ejemplares/nuevo', [
            'libro' => $libro
        ]);
    }

    // 📌 Guardar ejemplar
    public function create()
    {
        $data = [
            'libro_id' => $this->request->getPost('libro_id'),
            'estado'   => $this->request->getPost('estado'),
        ];

        $this->ejemplarModel->insert($data);

        // 🔹 actualizar cantidades del libro
        $libro = $this->libroModel->find($data['libro_id']);
        $updateData = [
            'cantidad_total' => $libro['cantidad_total'] + 1
        ];
        if ($data['estado'] === 'Disponible') {
            $updateData['cantidad_disponibles'] = $libro['cantidad_disponibles'] + 1;
        }
        $this->libroModel->update($data['libro_id'], $updateData);

        return redirect()->to(base_url('ejemplares/listar/'.$data['libro_id']))
                         ->with('msg', 'Ejemplar agregado correctamente.');
    }

    // 📌 Formulario editar ejemplar
    public function edit($ejemplar_id)
    {
        $ejemplar = $this->ejemplarModel->find($ejemplar_id);
        $libro = $this->libroModel->find($ejemplar['libro_id']);

        return view('Administrador/Libros/Ejemplares/edit', [
            'ejemplar' => $ejemplar,
            'libro' => $libro
        ]);
    }

    // 📌 Actualizar ejemplar
    public function update($ejemplar_id)
    {
        $ejemplar = $this->ejemplarModel->find($ejemplar_id);
        $nuevoEstado = $this->request->getPost('estado');

        $this->ejemplarModel->update($ejemplar_id, ['estado' => $nuevoEstado]);

        // 🔹 actualizar cantidad disponibles si cambió el estado
        if ($ejemplar['estado'] !== $nuevoEstado) {
            $libro = $this->libroModel->find($ejemplar['libro_id']);

            if ($nuevoEstado === 'Disponible') {
                $this->libroModel->update($libro['libro_id'], [
                    'cantidad_disponibles' => $libro['cantidad_disponibles'] + 1
                ]);
            } elseif ($ejemplar['estado'] === 'Disponible' && $nuevoEstado !== 'Disponible') {
                $this->libroModel->update($libro['libro_id'], [
                    'cantidad_disponibles' => max(0, $libro['cantidad_disponibles'] - 1)
                ]);
            }
        }

        return redirect()->to(base_url('ejemplares/listar/'.$ejemplar['libro_id']))
                         ->with('msg', 'Ejemplar actualizado correctamente.');
    }

    // 📌 Eliminar ejemplar
    public function delete($ejemplar_id)
    {
        $ejemplar = $this->ejemplarModel->find($ejemplar_id);
        $libro = $this->libroModel->find($ejemplar['libro_id']);

        $this->ejemplarModel->delete($ejemplar_id);

        // 🔹 actualizar cantidades del libro
        $updateData = [
            'cantidad_total' => max(0, $libro['cantidad_total'] - 1)
        ];
        if ($ejemplar['estado'] === 'Disponible') {
            $updateData['cantidad_disponibles'] = max(0, $libro['cantidad_disponibles'] - 1);
        }
        $this->libroModel->update($libro['libro_id'], $updateData);

        return redirect()->to(base_url('ejemplares/listar/'.$ejemplar['libro_id']))
                         ->with('msg', 'Ejemplar eliminado correctamente.');
    }
}
