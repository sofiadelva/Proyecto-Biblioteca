<?php
namespace App\Controllers;

use App\Models\PrestamoModel;
use App\Models\LibroModel;
use App\Models\EjemplarModel;
use App\Models\UsuarioModel; 
use CodeIgniter\Controller;

class Prestamos extends Controller
{
    /**
     * Muestra el formulario de creación de préstamo.
     */
    public function create()
    {
        return view('Administrador/Gestion/prestamos', ['libros' => []]);
    }

    /**
     * Obtener ejemplares disponibles de un libro (Usado por AJAX/Fetch).
     * @param int $libro_id ID del libro
     * @return \CodeIgniter\HTTP\Response
     */
    public function getEjemplares($libro_id)
    {
        $ejemplarModel = new EjemplarModel();

        $ejemplares = $ejemplarModel
            ->where('libro_id', $libro_id)
            ->where('estado', 'Disponible')
            ->findAll();

        return $this->response->setJSON($ejemplares);
    }

    /**
     * Guarda un nuevo préstamo en la base de datos.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $prestamoModel = new PrestamoModel();
        $usuarioModel  = new UsuarioModel();
        $ejemplarModel = new EjemplarModel();

        $carne  = $this->request->getPost('carne');
        $fecha_prestamo = $this->request->getPost('fecha_prestamo');
        $fecha_devolucion = $this->request->getPost('fecha_de_devolucion');
        
        $usuario = $usuarioModel->where('carne', $carne)->first();

        // 1. Validación clave del usuario
        $validation = \Config\Services::validation();
        if (!$usuario) {
            $validation->setError('carne', 'El usuario con carné ' . esc($carne) . ' no existe.');
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // 2. Validación de campos obligatorios (sin la regla after_date)
        $rules = [
            'carne'               => 'required',
            'libro_id'            => 'required|is_natural_no_zero',
            'ejemplar_id'         => 'required|is_natural_no_zero',
            'fecha_prestamo'      => 'required|valid_date',
            'fecha_de_devolucion' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Convertimos las fechas a objetos DateTime para poder compararlas
        try {
            $fechaPrestamoObj = new \DateTime($fecha_prestamo);
            $fechaDevolucionObj = new \DateTime($fecha_devolucion);

            if ($fechaDevolucionObj <= $fechaPrestamoObj) {
                // Si la fecha de devolución es menor o igual a la de préstamo, fallamos la validación.
                $validation->setError('fecha_de_devolucion', 'La fecha de devolución debe ser posterior a la fecha de préstamo.');
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        } catch (\Exception $e) {
            // Esto solo se ejecutaría si valid_date falló, pero es una buena práctica incluirlo.
            $validation->setError('fecha_de_devolucion', 'Formato de fecha inválido.');
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // 4. Guardar el préstamo si la validación manual fue exitosa
        $data = [
            'libro_id'            => $this->request->getPost('libro_id'),
            'ejemplar_id'         => $this->request->getPost('ejemplar_id'),
            'usuario_id'          => $usuario['usuario_id'], 
            'fecha_prestamo'      => $fecha_prestamo,
            'fecha_de_devolucion' => $fecha_devolucion,
            'estado'              => 'En proceso'
        ];

        if ($prestamoModel->insert($data)) {
            // Cambiar estado del ejemplar a "No disponible"
            $ejemplarModel->update(
                $this->request->getPost('ejemplar_id'),
                ['estado' => 'No disponible']
            );
            return redirect()->to(base_url('prestamos'))->with('msg', 'Préstamo agregado correctamente.');
        } else {
             return redirect()->back()->withInput()->with('msg', 'Error al guardar el préstamo en la base de datos.');
        }
    }

    /**
     * Obtener libros para la búsqueda dinámica (Select2)
     * @return \CodeIgniter\HTTP\Response
     */
    public function getLibrosJson()
    {
        $libroModel = new LibroModel();
        $term = $this->request->getGet('term');
        $id = $this->request->getGet('id'); 

        $query = $libroModel->select('libro_id as id, titulo, autor, cantidad_disponibles');
        
        $query->where('estado', 'Disponible');

        if (!empty($term)) {
            $query->groupStart()
                  ->like('titulo', $term)
                  ->orLike('autor', $term)
                  ->groupEnd();
        } elseif (!empty($id)) {
            $query->where('libro_id', $id);
        }

        $libros = $query->findAll();

        // Formatear resultados para Select2
        $results = array_map(function($libro) {
            $text = "{$libro['titulo']} (Autor: {$libro['autor']}) - Disp: {$libro['cantidad_disponibles']}";
            return ['id' => $libro['id'], 'text' => $text, 'cantidad_disponibles' => $libro['cantidad_disponibles']];
        }, $libros);
        
        return $this->response->setJSON(['results' => $results]);
    }
}