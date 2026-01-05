<?php

namespace App\Controllers;

// Usamos Dompdf en lugar de TCPDF
use Dompdf\Dompdf; 
use Dompdf\Options; // Necesario para configurar opciones como fuente y parser

use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\PrestamoAlumnoModel;

class Reportes extends BaseController
{
    // Color principal verde oscuro para el reporte (Hex: #095959)
    const COLOR_HEADER_BG = '#0C1E44';
    // Colores de fila ya no se usan como constantes directas, sino en el CSS
    // Color para estado Vencido/Perdido
    const COLOR_VENCIDO = '#dc3545';
    // Color para estado Devuelto
    const COLOR_DEVUELTO = '#198754';


    /**
     * Genera el encabezado con CSS moderno que Dompdf sí soporta (usando float y estilos directos).
     */
    private function getReporteHeader($titulo)
    {
        $logoPath = FCPATH . 'img/scj.png'; 
        
        // 1. CSS moderno compatible con Dompdf
        $html = '<style>
            body { 
                font-family: "Helvetica", Arial, sans-serif; 
                font-size: 10pt; 
                color: #333;
            }
            /* Estilos para el encabezado superior */
            .logo-container { 
                float: left; 
                width: 50%;
            }
            .header-info { 
                float: right; 
                text-align: right; 
                font-size: 9.5pt; 
                color: #555;
                width: 50%;
            }
            /* Estilos para el título del reporte */
            h2 { 
                color: ' . self::COLOR_HEADER_BG . '; 
                border-bottom: 3px solid ' . self::COLOR_HEADER_BG . '; 
                padding-bottom: 8px; 
                margin-top: 30px; /* Separación después del header flotante */
                font-size: 16pt; 
                font-weight: 700;
                clear: both; /* Importante para limpiar el float */
            }
            /* Estilos de la tabla */
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 15px;
            }
            th, td { 
                padding: 10px 12px; 
                text-align: left; 
                border-bottom: 1px solid #e0e0e0; /* Borde inferior ligero */
                font-size: 10pt;
                vertical-align: middle;
            }
            th { 
                background-color: ' . self::COLOR_HEADER_BG . '; 
                color: #ffffff; 
                font-weight: 600; 
                text-transform: uppercase;
                text-align: center;
                border: 1px solid ' . self::COLOR_HEADER_BG . '; /* Borde completo en th para mejor look */
            }
            /* Dompdf soporta filas alternas con pseudo-clases */
            tr:nth-child(even) {
                background-color: #f5f9f9; 
            }
            .text-center { text-align: center; }
        </style>';

        // 2. Estructura de Encabezado usando DIVs con float
        $logoTag = file_exists($logoPath) ? '<img src="' . $logoPath . '" style="width: 70px; height: auto;">' : '';

        $html .= '<div class="logo-container">' . $logoTag . '</div>';
        $html .= '<div class="header-info">
                    <strong>Reporte generado:</strong> ' . date('d/m/Y H:i:s') . '<br>
                    ReadZone SCJ
                  </div>';
        
        $html .= '<h2>' . $titulo . '</h2>';
        
        return $html;
    }

    /**
     * Aplica estilos de color a los estados de los préstamos usando CSS in-line simple.
     */
    private function formatEstado($estado)
    {
        if ($estado == 'Vencido' || $estado == 'Perdido') {
            return '<span style="color:' . self::COLOR_VENCIDO . '; font-weight: bold;">' . $estado . '</span>';
        } elseif ($estado == 'Devuelto') {
            return '<span style="color:' . self::COLOR_DEVUELTO . '; font-weight: bold;">' . $estado . '</span>';
        }
        return $estado;
    }

    public function index()
    {
        return view('Administrador/Reportes/index');
    }

    // ============================= REPORTE POR ALUMNO (VISTA Y GENERACIÓN) =============================
    
    // ============================= REPORTE POR ALUMNO (VISTA Y GENERACIÓN) =============================
    
public function alumnoView()
{
    $usuarioModel = new UsuarioModel();
    $prestamoModel = new PrestamoAlumnoModel();
    
    $alumnos = $usuarioModel->select('carne, nombre')->whereIn('rol', ['Alumno', 'Docente'])->findAll();

    $busqueda = $this->request->getGet('usuario_nombre'); 
    $perPage = (int) ($this->request->getGet('per_page') ?? 10);
    if ($perPage < 1) $perPage = 10;

    $query = $prestamoModel
        ->select('libros.codigo, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado, usuarios.nombre as alumno')
        ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id') 
        ->join('libros', 'libros.libro_id = prestamos.libro_id')
        ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

    // Variable para mostrar en el h3 de la vista
    $nombreParaMostrar = $busqueda; 

    if ($busqueda) {
        $usuario = $usuarioModel->groupStart()
                                ->where('carne', $busqueda)
                                ->orWhere('nombre', $busqueda)
                                ->groupEnd()
                                ->first();
        if ($usuario) {
            $query->where('prestamos.usuario_id', $usuario['usuario_id']);
            // AQUÍ ESTÁ EL TRUCO: 
            // Si lo encontró, reemplazamos el carné por el nombre real
            $nombreParaMostrar = $usuario['nombre']; 
        } else {
            $query->where('prestamos.usuario_id', 0);
        }
    }

    $prestamos = $query->paginate($perPage);

    return view('Administrador/Reportes/alumno', [
        'alumnos' => $alumnos,
        'prestamos' => $prestamos,
        'pager' => $prestamoModel->pager,
        'perPage' => $perPage,
        'nombreAlumno' => $nombreParaMostrar // Enviamos el nombre real
    ]);
}

    public function alumno()
    {
        $busqueda = $this->request->getPost('usuario_nombre');
        $usuarioModel = new UsuarioModel();
        
        $alumno = $usuarioModel->groupStart()
                                ->where('carne', $busqueda)
                                ->orWhere('nombre', $busqueda)
                                ->groupEnd()
                                ->first();

        $prestamoModel = new PrestamoAlumnoModel();
        $query = $prestamoModel
            ->select('libros.codigo, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');
        
        if ($alumno) {
            $query->where('prestamos.usuario_id', $alumno['usuario_id']);
            $titulo = 'Reporte de Préstamos: ' . esc($alumno['nombre']) . ' (' . esc($alumno['carne']) . ')';
        } else {
            $titulo = 'Reporte General de Usuarios';
        }
        
        $prestamos = $query->findAll();
        $html = $this->getReporteHeader($titulo);
        
        $html .= '<table><thead><tr><th>#</th><th>Código</th><th>Libro</th><th>Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr></thead><tbody>';
        
        if (empty($prestamos)) {
            $html .= '<tr><td colspan="8" class="text-center">No hay registros.</td></tr>';
        } else {
            $cont = 1;
            foreach ($prestamos as $p) {
                $retraso = ($p['fecha_devuelto'] && $p['fecha_devuelto'] > $p['fecha_de_devolucion']) ? ' <span style="color:red;">[!]</span>' : '';
                $html .= "<tr>
                    <td class='text-center'>{$cont}</td>
                    <td class='text-center'>".esc($p['codigo'])."</td>
                    <td>".esc($p['titulo'])."</td>
                    <td class='text-center'>".esc($p['no_copia'])."</td>
                    <td class='text-center'>".esc($p['fecha_prestamo'])."</td>
                    <td class='text-center'>".esc($p['fecha_de_devolucion'])."</td>
                    <td class='text-center'>".($p['fecha_devuelto'] ?? '-')."{$retraso}</td>
                    <td class='text-center'>".$this->formatEstado($p['estado'])."</td>
                </tr>";
                $cont++;
            }
        }
        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('reporte_alumno.pdf', ["Attachment" => false]);
        exit;
    }
  // ============================= REPORTE POR LIBRO =============================

    public function libroView()
    {
        $libroModel = new LibroModel();
        // Cargamos código y título para el datalist
        $libros = $libroModel->select('codigo, titulo')->findAll();

        $perPage = (int) ($this->request->getGet('per_page') ?? 10);
        if ($perPage < 1) $perPage = 10;
        
        $busqueda = $this->request->getGet('libro_titulo');

        $prestamoModel = new PrestamoAlumnoModel();
        
        $query = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.codigo, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

        $tituloParaMostrar = $busqueda;

        if ($busqueda) {
            // Buscamos por código (prioridad) o por título
            $libro = $libroModel->groupStart()
                                ->where('codigo', $busqueda)
                                ->orWhere('titulo', $busqueda)
                                ->groupEnd()
                                ->first();

            if ($libro) {
                $query->where('prestamos.libro_id', $libro['libro_id']);
                $tituloParaMostrar = $libro['titulo']; // "Traducimos" el código al título real
            } else {
                $query->where('prestamos.libro_id', 0);
            }
        }

        $prestamos = $query->paginate($perPage);

        return view('Administrador/Reportes/libro', [
            'libros' => $libros,
            'prestamos' => $prestamos,
            'pager' => $prestamoModel->pager,
            'perPage' => $perPage,
            'tituloLibro' => $tituloParaMostrar
        ]);
    }

    public function libro()
    {
        $busqueda = $this->request->getPost('libro_titulo');
        $libroModel = new LibroModel();
        $prestamoModel = new PrestamoAlumnoModel();
        
        $libro = $libroModel->groupStart()
                            ->where('codigo', $busqueda)
                            ->orWhere('titulo', $busqueda)
                            ->groupEnd()
                            ->first();

        $query = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.codigo, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

        if ($libro) {
            $query->where('prestamos.libro_id', $libro['libro_id']);
            $tituloDoc = 'Reporte de Préstamos: ' . esc($libro['titulo']) . ' (' . esc($libro['codigo']) . ')';
        } else {
            $tituloDoc = 'Reporte General de Préstamos (Libros)';
        }
        
        $prestamos = $query->findAll();
        $html = $this->getReporteHeader($tituloDoc);
        
        $html .= '<table><thead><tr><th>#</th><th>Código</th><th>Libro</th><th>Alumno</th><th>Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr></thead><tbody>';
        
        if (empty($prestamos)) {
            $html .= '<tr><td colspan="9" style="text-align:center;">No hay registros.</td></tr>';
        } else {
            $cont = 1;
            foreach ($prestamos as $p) {
                $retraso = ($p['fecha_devuelto'] && $p['fecha_devuelto'] > $p['fecha_de_devolucion']) ? ' <span style="color:red; font-weight:bold;">[!]</span>' : '';
                $html .= "<tr>
                    <td style='text-align:center;'>{$cont}</td>
                    <td style='text-align:center;'>".esc($p['codigo'])."</td>
                    <td>".esc($p['titulo'])."</td>
                    <td>".esc($p['alumno'])."</td>
                    <td style='text-align:center;'>".esc($p['no_copia'])."</td>
                    <td style='text-align:center;'>".esc($p['fecha_prestamo'])."</td>
                    <td style='text-align:center;'>".esc($p['fecha_de_devolucion'])."</td>
                    <td style='text-align:center;'>".($p['fecha_devuelto'] ?? '-')."{$retraso}</td>
                    <td style='text-align:center;'>".$this->formatEstado($p['estado'])."</td>
                </tr>";
                $cont++;
            }
        }
        $html .= '</tbody></table>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('reporte_libro.pdf', ["Attachment" => false]);
        exit;
    }

    // ============================= REPORTES ACTIVOS (VISTA Y GENERACIÓN) =============================

    public function activosView()
    {
        $prestamoModel = new PrestamoAlumnoModel();
        
        // 1. Arreglo del filtrado de filas (evita errores si viene vacío o no es número)
        $perPageInput = $this->request->getGet('per_page');
        $perPage = (is_numeric($perPageInput) && $perPageInput > 0) ? (int)$perPageInput : 10;

        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, usuarios.carne, libros.titulo, libros.codigo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.fecha_devuelto', null) // Solo los que no han sido devueltos
            ->paginate($perPage);

        return view('Administrador/Reportes/activo', [
            'prestamos' => $prestamos,
            'pager'     => $prestamoModel->pager,
            'perPage'   => $perPage,
            'hoy'       => date('Y-m-d') // Enviamos la fecha de hoy para comparar retrasos
        ]);
    }

    public function prestamosActivos()
    {
        $prestamoModel = new PrestamoAlumnoModel();
        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, usuarios.carne, libros.titulo, libros.codigo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.fecha_devuelto', null)
            ->findAll();

        $html = $this->getReporteHeader('Reporte de Préstamos Activos');
        $hoy = date('Y-m-d');
        
        $html .= '<table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th>Libro</th>
                        <th>Copia</th>
                        <th>Préstamo</th>
                        <th>Entrega Esperada</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>';

        if (empty($prestamos)) {
            $html .= '<tr><td colspan="8" style="text-align:center;">No hay préstamos activos.</td></tr>';
        } else {
            $cont = 1;
            foreach ($prestamos as $p) {
                // Marca de retraso: Si hoy es mayor a la fecha de devolución
                $retraso = ($hoy > $p['fecha_de_devolucion']) ? ' <span style="color:red; font-weight:bold;">[!]</span>' : '';
                
                $html .= "<tr>
                            <td style='text-align:center;'>{$cont}</td>
                            <td>" . esc($p['alumno']) . "<br><small style='color:#666;'>" . esc($p['carne']) . "</small></td>
                            <td style='text-align:center;'>" . esc($p['codigo']) . "</td>
                            <td>" . esc($p['titulo']) . "</td>
                            <td style='text-align:center;'>" . esc($p['no_copia']) . "</td>
                            <td style='text-align:center;'>" . esc($p['fecha_prestamo']) . "</td>
                            <td style='text-align:center;'>" . esc($p['fecha_de_devolucion']) . "{$retraso}</td>
                            <td style='text-align:center;'>" . $this->formatEstado($p['estado']) . "</td>
                        </tr>";
                $cont++;
            }
        }
        $html .= '</tbody></table>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Landscape para que quepa el carné y código
        $dompdf->render();
        $dompdf->stream('reporte_activos.pdf', ["Attachment" => false]);
        exit;
    }

    // ============================= REPORTES DISPONIBLES (VISTA Y GENERACIÓN) =============================

    public function disponiblesView()
    {
        $libroModel = new LibroModel();
        $perPage = $this->request->getGet('per_page') ?? 5;

        $libros = $libroModel->where('estado', 'Disponible')->paginate($perPage);
        $pager = $libroModel->pager;

        return view('Administrador/Reportes/disponibles', [
            'libros' => $libros,
            'pager' => $pager,
            'perPage' => $perPage
        ]);
    }

    /**
     * Genera el PDF de libros disponibles usando Dompdf.
     */
    public function librosDisponibles()
    {
        $libroModel = new LibroModel();
        $libros = $libroModel->where('estado', 'Disponible')->findAll();

        $html = $this->getReporteHeader('Reporte de Libros Disponibles en Inventario');

        $html .= '<table>
                     <thead>
                     <tr><th>Título</th><th>Autor</th><th>Editorial</th><th>Cantidad Disponible</th></tr>
                     </thead>
                     <tbody>';

        if (empty($libros)) {
             $html .= '<tr><td colspan="4" class="text-center">No hay libros disponibles en el inventario.</td></tr>';
        } else {
            foreach ($libros as $l) {
                // Dompdf usa el CSS :nth-child(even), no necesitamos bgcolor ni $i++
                $html .= "<tr>
                                <td>" . esc($l['titulo']) . "</td>
                                <td>" . esc($l['autor']) . "</td>
                                <td>" . esc($l['editorial']) . "</td>
                                <td class='text-center'>" . esc($l['cantidad_disponibles']) . "</td>
                            </tr>";
            }
        }
        
        $html .= '</tbody></table>';

        // Lógica de DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true); 
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // El parámetro ["Attachment" => false] permite que el PDF se abra en el navegador (nueva ventana/pestaña)
        $dompdf->stream('reporte_disponibles.pdf', ["Attachment" => false]);
        exit;
    }
}