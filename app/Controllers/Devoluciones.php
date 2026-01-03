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

        // 1. Obtener parámetros
        $buscar = $this->request->getGet('buscar');
        $filtro_estado = $this->request->getGet('filtro_estado'); // vigente o atrasado
        $ordenar = $this->request->getGet('ordenar') ?? 'vencimiento_asc';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        
        $hoy = new \DateTime('today');

        // 2. Carga de datos inicial
        $prestamos = $prestamoModel->where('estado', 'En proceso')->findAll();
        
        foreach ($prestamos as &$prestamo) {
            $usuario   = $usuarioModel->find($prestamo['usuario_id']);
            $libro     = $libroModel->find($prestamo['libro_id']);
            $ejemplar  = $ejemplarModel->find($prestamo['ejemplar_id']);

            $prestamo['carne']          = $usuario['carne'] ?? '';
            $prestamo['nombre_usuario'] = $usuario['nombre'] ?? 'Desconocido';
            $prestamo['titulo']         = $libro['titulo'] ?? 'Libro Eliminado';
            $prestamo['codigo']         = $libro['codigo'] ?? 'S/C'; 
            $prestamo['no_copia']       = $ejemplar['no_copia'] ?? 'N/A';
            
            // Determinamos el estado para poder filtrar después
            $limite = new \DateTime($prestamo['fecha_de_devolucion']);
            $prestamo['es_atrasado'] = $hoy > $limite;
        }

        // 3. Filtrar por Búsqueda (Actualizado para incluir código)
        if (!empty($buscar)) {
            $prestamos = array_filter($prestamos, function ($p) use ($buscar) {
                $b = strtolower($buscar);
                return stripos($p['carne'], $b) !== false || 
                    stripos($p['nombre_usuario'], $b) !== false ||
                    stripos($p['titulo'], $b) !== false || 
                    stripos($p['codigo'], $b) !== false; // <--- Esta es la línea clave
            });
        }

        // 4. Filtrar por Estado (Vigente / Atrasado)
        if (!empty($filtro_estado)) {
            $prestamos = array_filter($prestamos, function ($p) use ($filtro_estado) {
                if ($filtro_estado === 'atrasado') return $p['es_atrasado'];
                if ($filtro_estado === 'vigente') return !$p['es_atrasado'];
                return true;
            });
        }
        
        // 5. Ordenamiento
        usort($prestamos, function ($a, $b) use ($ordenar) {
            $timeA = strtotime($a['fecha_de_devolucion']);
            $timeB = strtotime($b['fecha_de_devolucion']);
            
            switch ($ordenar) {
                case 'vencimiento_desc': return $timeB <=> $timeA; // Más lejanos primero
                case 'usuario_asc': return strcmp($a['nombre_usuario'], $b['nombre_usuario']);
                case 'vencimiento_asc': 
                default: return $timeA <=> $timeB; // Más próximos/atrasados primero
            }
        });

        // 6. Paginación
        $total = count($prestamos);
        $offset = ($page - 1) * $perPage;
        $prestamosPaginados = array_slice($prestamos, $offset, $perPage);

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $total, 'bootstrap_full');
        
        return view('Administrador/Gestion/devoluciones', [
            'prestamos'     => $prestamosPaginados,
            'pager'         => $pager,
            'buscar'        => $buscar,
            'perPage'       => $perPage,
            'filtro_estado' => $filtro_estado,
            'ordenar'       => $ordenar
        ]);
    }
    /**
     * Confirma la devolución de un libro, guarda la fecha de hoy y actualiza los estados.
     * @param int $prestamo_id ID del préstamo a confirmar.
     */
    public function confirmar($prestamo_id)
    {
        $prestamoModel = new PrestamoModel();
        $ejemplarModel = new EjemplarModel();
        $libroModel    = new LibroModel(); // <--- Instanciamos el modelo de libros
        
        $prestamo = $prestamoModel->find($prestamo_id);

        if (!$prestamo || $prestamo['estado'] !== 'En proceso') {
            return redirect()->to(base_url('devoluciones'))
                             ->with('error_msg', 'Préstamo no encontrado o ya fue devuelto.');
        }
        
        $fecha_actual = date('Y-m-d'); 

        // 1. Actualizar el estado del préstamo
        $prestamoModel->update($prestamo_id, [
            'estado' => 'Devuelto',
            'fecha_devuelto' => $fecha_actual 
        ]);

        // 2. Actualizar el estado del ejemplar (volverlo disponible)
        $ejemplarModel->update($prestamo['ejemplar_id'], [
            'estado' => 'Disponible'
        ]);

        // 3. ACTUALIZACIÓN DEL LIBRO MADRE: Sumar 1 al stock disponible
        $libroModel->where('libro_id', $prestamo['libro_id'])
                   ->set('cantidad_disponibles', 'cantidad_disponibles + 1', false)
                   ->update();

        return redirect()->to(base_url('devoluciones'))
                         ->with('msg', 'Devolución confirmada correctamente. El stock del libro ha sido actualizado.');
    }
}