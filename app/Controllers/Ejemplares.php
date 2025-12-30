<?php

namespace App\Controllers;

use App\Models\EjemplarModel;
use App\Models\LibroModel;
use CodeIgniter\Controller; // Usar Controller o BaseController según la herencia de tu proyecto

class Ejemplares extends Controller
{
    protected $ejemplarModel;
    protected $libroModel;

    public function __construct()
    {
        $this->ejemplarModel = new EjemplarModel();
        $this->libroModel = new LibroModel();
    }

    /**
     * Listar ejemplares de un libro específico.
     * @param int $libro_id ID del libro.
     */
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

    /**
     * Muestra el formulario para agregar un nuevo ejemplar a un libro.
     * @param int $libro_id ID del libro al que se agregará el ejemplar.
     */
    public function new($libro_id)
    {
        $libro = $this->libroModel->find($libro_id);

        return view('Administrador/Libros/Ejemplares/nuevo', [
            'libro' => $libro
        ]);
    }

    /**
     * Guarda un nuevo ejemplar en la base de datos y actualiza las cantidades del libro.
     */
    public function create()
    {
        $libro_id = $this->request->getPost('libro_id');
        

        // 1. Obtener el último número de copia para este libro
        $ultimoEjemplar = $this->ejemplarModel
            ->where('libro_id', $libro_id)
            ->orderBy('no_copia', 'DESC')
            ->first();
            
        // 2. El nuevo número de copia es el último encontrado + 1. Si no hay, empieza en 1.
        $nuevoNoCopia = ($ultimoEjemplar ? $ultimoEjemplar['no_copia'] : 0) + 1;

        $data = [
            'libro_id' => $libro_id,
            'estado'   => $this->request->getPost('estado'),
            'no_copia' => $nuevoNoCopia // Asignamos el número de copia calculado
        ];

        $this->ejemplarModel->insert($data);

        // 🔹 Actualizar cantidades del libro
        $libro = $this->libroModel->find($data['libro_id']);
        $updateData = [
            'cantidad_total' => $libro['cantidad_total'] + 1
        ];
        
        if ($data['estado'] === 'Disponible') {
            $updateData['cantidad_disponibles'] = $libro['cantidad_disponibles'] + 1;
        }
        
        $this->libroModel->update($data['libro_id'], $updateData);

        return redirect()->to(base_url('ejemplares/listar/'.$data['libro_id']))
                         ->with('msg', 'Ejemplar agregado correctamente. Número de copia: ' . $nuevoNoCopia);
    }

    /**
     * Muestra el formulario para editar un ejemplar específico.
     * @param int $ejemplar_id ID del ejemplar a editar.
     */
    public function edit($ejemplar_id)
    {
        $ejemplar = $this->ejemplarModel->find($ejemplar_id);
        $libro = $this->libroModel->find($ejemplar['libro_id']);

        return view('Administrador/Libros/Ejemplares/edit', [
            'ejemplar' => $ejemplar,
            'libro' => $libro
        ]);
    }

    /**
     * Actualiza el estado de un ejemplar y ajusta las cantidades del libro.
     * @param int $ejemplar_id ID del ejemplar a actualizar.
     */
    public function update($ejemplar_id)
{
    $ejemplar = $this->ejemplarModel->find($ejemplar_id);
    $nuevoEstado = $this->request->getPost('estado');

    // Si el libro está PRESTADO, bloqueamos el cambio de estado manual
    if ($ejemplar['estado'] === 'Prestado') {
        return redirect()->back()->with('msg_error', 'No puedes cambiar el estado de un libro que está prestado actualmente.');
    }

    $this->ejemplarModel->update($ejemplar_id, ['estado' => $nuevoEstado]);

    // Lógica de conteo de disponibles
    if ($ejemplar['estado'] !== $nuevoEstado) {
        $libro = $this->libroModel->find($ejemplar['libro_id']);

        if ($nuevoEstado === 'Disponible') {
            // Si pasa de Dañado -> Disponible
            $this->libroModel->update($libro['libro_id'], [
                'cantidad_disponibles' => $libro['cantidad_disponibles'] + 1
            ]);
        } elseif ($ejemplar['estado'] === 'Disponible' && $nuevoEstado === 'Dañado') {
            // Si pasa de Disponible -> Dañado
            $this->libroModel->update($libro['libro_id'], [
                'cantidad_disponibles' => max(0, $libro['cantidad_disponibles'] - 1)
            ]);
        }
    }

    return redirect()->to(base_url('ejemplares/listar/'.$ejemplar['libro_id']))
                     ->with('msg', 'Estado actualizado correctamente.');
}

    /**
     * Elimina un ejemplar y actualiza las cantidades del libro.
     * @param int $ejemplar_id ID del ejemplar a eliminar.
     */
    public function delete($ejemplar_id)
    {
        $ejemplar = $this->ejemplarModel->find($ejemplar_id);
        $libro = $this->libroModel->find($ejemplar['libro_id']);

        $this->ejemplarModel->delete($ejemplar_id);

        // Actualizar cantidades del libro (Total siempre disminuye)
        $updateData = [
            'cantidad_total' => max(0, $libro['cantidad_total'] - 1)
        ];
        
        // Si el ejemplar eliminado estaba disponible, disminuir también esa cantidad
        if ($ejemplar['estado'] === 'Disponible') {
            $updateData['cantidad_disponibles'] = max(0, $libro['cantidad_disponibles'] - 1);
        }
        
        $this->libroModel->update($libro['libro_id'], $updateData);

        return redirect()->to(base_url('ejemplares/listar/'.$ejemplar['libro_id']))
                         ->with('msg', 'Ejemplar eliminado correctamente.');
    }
}