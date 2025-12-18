<?php

namespace App\Controllers;

use App\Models\PrestamoModel;
use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\EjemplarModel;
use CodeIgniter\Controller;
use CodeIgniter\Pager\Pager;

class Devoluciones extends Controller
{
    /**
     * Muestra lista de préstamos en proceso con opción de búsqueda y ordenamiento.
     */
    public function index()
    {
        $prestamoModel = new PrestamoModel();
        $usuarioModel  = new UsuarioModel();
        $libroModel    = new LibroModel();
        $ejemplarModel = new EjemplarModel();

        // Obtener parámetros de URL
        $buscar = $this->request->getGet('buscar');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        // 1. Obtener todos los préstamos en proceso
        $prestamos = $prestamoModel->where('estado', 'En proceso')->findAll();
        
        // 2. Eager Loading (Carga anticipada) y enriquecimiento de datos
        foreach ($prestamos as $key => &$prestamo) {
            $usuario   = $usuarioModel->find($prestamo['usuario_id']);
            $libro     = $libroModel->find($prestamo['libro_id']);
            $ejemplar  = $ejemplarModel->find($prestamo['ejemplar_id']);

            // Añadir campos necesarios para la vista
            $prestamo['carne']          = $usuario['carne'] ?? '';
            $prestamo['nombre_usuario'] = $usuario['nombre'] ?? 'Desconocido';
            $prestamo['titulo']         = $libro['titulo'] ?? 'Libro Eliminado';
            $prestamo['no_copia']       = $ejemplar['no_copia'] ?? 'N/A';
        }

        // 3. Filtrar por búsqueda (Funcional)
        if (!empty($buscar)) {
            $prestamos = array_filter($prestamos, function ($p) use ($buscar) {
                $buscarLower = strtolower($buscar);
                // Búsqueda en nombre, carné o título del libro
                return stripos($p['carne'], $buscarLower) !== false
                    || stripos($p['nombre_usuario'], $buscarLower) !== false
                    || stripos($p['titulo'], $buscarLower) !== false;
            });
        }
        
        // 4. Ordenamiento Manual: Límite más cercano/atrasado primero
        usort($prestamos, function ($a, $b) {
            $aTime = strtotime($a['fecha_de_devolucion']);
            $bTime = strtotime($b['fecha_de_devolucion']);
            return $aTime <=> $bTime;
        });


        // 5. Paginación Manual
        $total = count($prestamos);
        $offset = ($page - 1) * $perPage;
        $prestamosPaginados = array_slice($prestamos, $offset, $perPage);

        // Crear Paginador Manual
        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'bootstrap_full');
        
        $data = [
            'prestamos' => $prestamosPaginados,
            'pager'     => $pager,
            'buscar'    => $buscar,
            'perPage'   => $perPage
        ];

        return view('Administrador/Gestion/devoluciones', $data);
    }
    
    /**
     * Confirma la devolución de un libro, guarda la fecha de hoy y actualiza los estados.
     * @param int $prestamo_id ID del préstamo a confirmar.
     */
    public function confirmar($prestamo_id)
    {
        $prestamoModel = new PrestamoModel();
        $ejemplarModel = new EjemplarModel();
        
        $prestamo = $prestamoModel->find($prestamo_id);

        if (!$prestamo || $prestamo['estado'] !== 'En proceso') {
            return redirect()->to(base_url('devoluciones'))
                             ->with('error_msg', 'Préstamo no encontrado o ya fue devuelto.');
        }
        
        // Obtiene la fecha actual en formato AAAA-MM-DD
        $fecha_actual = date('Y-m-d'); 

        // 1. Actualizar el estado del préstamo y registrar la fecha de devolución real
        $prestamoModel->update($prestamo_id, [
            'estado' => 'Devuelto',
            // ✅ CORRECCIÓN FINAL: Usamos 'fecha_devuelto'
            'fecha_devuelto' => $fecha_actual 
        ]);

        // 2. Actualizar el estado del ejemplar (volverlo disponible)
        $ejemplarModel->update($prestamo['ejemplar_id'], [
            'estado' => 'Disponible'
        ]);

        return redirect()->to(base_url('devoluciones'))
                         ->with('msg', 'Devolución confirmada correctamente en la fecha ' . $fecha_actual . '.');
    }
}