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
    const COLOR_HEADER_BG = '#095959';
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
        $logoPath = FCPATH . 'img/logo.png'; 
        
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
                    Biblioteca Central
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
    
    public function alumnoView()
    {
        $usuarioModel = new UsuarioModel();
        $alumnos = $usuarioModel->where('rol', 'Alumno')->findAll();

        $perPage = $this->request->getGet('per_page') ?? 5;
        $nombreAlumno = $this->request->getGet('usuario_nombre');

        $prestamoModel = new PrestamoAlumnoModel();
        
        $query = $prestamoModel
            ->select('libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado, usuarios.nombre as alumno')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id') 
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

        if ($nombreAlumno) {
            $usuario = $usuarioModel->where('nombre', $nombreAlumno)->first();
            if ($usuario) {
                $query->where('prestamos.usuario_id', $usuario['usuario_id']);
            }
        }

        $prestamos = $query->paginate($perPage);
        $pager = $prestamoModel->pager;

        return view('Administrador/Reportes/alumno', [
            'alumnos' => $alumnos,
            'prestamos' => $prestamos,
            'pager' => $pager,
            'perPage' => $perPage,
            'nombreAlumno' => $nombreAlumno
        ]);
    }
    
    /**
     * Genera el PDF de préstamos por alumno usando Dompdf.
     */
    public function alumno()
    {
        $nombreAlumno = $this->request->getPost('usuario_nombre');

        $usuarioModel = new UsuarioModel();
        $alumno = $usuarioModel->where('nombre', $nombreAlumno)->first();

        if ($nombreAlumno && !$alumno) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        $prestamoModel = new PrestamoAlumnoModel();
        $query = $prestamoModel
            ->select('libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');
        
        if ($alumno) {
            $query->where('prestamos.usuario_id', $alumno['usuario_id']);
        }
        
        $prestamos = $query->findAll();

        $titulo = 'Reporte de Préstamos Históricos';
        if($alumno) {
             $titulo .= ' del Alumno: ' . esc($alumno['nombre']);
        } else {
             $titulo .= ' (Todos los Alumnos)';
        }

        $html = $this->getReporteHeader($titulo);
        
        $html .= '<table>
                     <thead>
                     <tr><th style="width: 30%;">Título</th><th style="width: 10%;">Copia</th><th style="width: 15%;">Préstamo</th><th style="width: 15%;">Devolución</th><th style="width: 15%;">Devuelto</th><th style="width: 15%;">Estado</th></tr>
                     </thead>
                     <tbody>';

        if (empty($prestamos)) {
             $html .= '<tr><td colspan="6" class="text-center">No hay préstamos registrados.</td></tr>';
        } else {
             foreach ($prestamos as $p) {
                 $estadoFormateado = $this->formatEstado($p['estado']);
                 // Dompdf usa el CSS :nth-child(even), no necesitamos bgcolor ni $i++
                 $html .= "<tr>
                                <td>" . esc($p['titulo']) . "</td>
                                <td class='text-center'>" . esc($p['no_copia']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_prestamo']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_de_devolucion']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_devuelto'] ?? '-') . "</td>
                                <td class='text-center'>{$estadoFormateado}</td>
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
        $dompdf->stream('reporte_alumno.pdf', ["Attachment" => false]); 
        exit;
    }

    // ============================= REPORTE POR LIBRO (VISTA Y GENERACIÓN) =============================

    public function libroView()
    {
        $libroModel = new LibroModel();
        $libros = $libroModel->findAll();

        $perPage = $this->request->getGet('per_page') ?? 5;
        $tituloLibro = $this->request->getGet('libro_titulo');

        $prestamoModel = new PrestamoAlumnoModel();
        
        $query = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

        if ($tituloLibro) {
            $libro = $libroModel->where('titulo', $tituloLibro)->first();
            if ($libro) {
                $query->where('prestamos.libro_id', $libro['libro_id']);
            }
        }

        $prestamos = $query->paginate($perPage);
        $pager = $prestamoModel->pager;

        return view('Administrador/Reportes/libro', [
            'libros' => $libros,
            'prestamos' => $prestamos,
            'pager' => $pager,
            'perPage' => $perPage,
            'tituloLibro' => $tituloLibro
        ]);
    }

    /**
     * Genera el PDF de préstamos por libro usando Dompdf.
     */
    public function libro()
    {
        $tituloLibro = $this->request->getPost('libro_titulo');
        $libroModel = new LibroModel();
        $prestamoModel = new PrestamoAlumnoModel();
        
        $query = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');

        $libro = null;

        if ($tituloLibro) {
            $libro = $libroModel->where('titulo', $tituloLibro)->first(); 
            if ($libro) {
                $query->where('prestamos.libro_id', $libro['libro_id']);
            }
        }
        
        $prestamos = $query->findAll();

        $titulo = 'Reporte Histórico de Préstamos';
        if ($libro) {
            $titulo .= ' del Libro: ' . esc($libro['titulo']);
        } else {
            $titulo .= ' (Todos los Libros)';
        }

        $html = $this->getReporteHeader($titulo);
        
        $html .= '<table>
                     <thead>
                     <tr><th>Alumno</th><th>Título</th><th>Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr>
                     </thead>
                     <tbody>';

        if (empty($prestamos)) {
             $html .= '<tr><td colspan="7" class="text-center">No hay préstamos registrados.</td></tr>';
        } else {
            foreach ($prestamos as $p) {
                $estadoFormateado = $this->formatEstado($p['estado']);
                // Dompdf usa el CSS :nth-child(even), no necesitamos bgcolor ni $i++
                $html .= "<tr>
                                <td>" . esc($p['alumno']) . "</td>
                                <td>" . esc($p['titulo']) . "</td>
                                <td class='text-center'>" . esc($p['no_copia']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_prestamo']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_de_devolucion']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_devuelto'] ?? '-') . "</td>
                                <td class='text-center'>{$estadoFormateado}</td>
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
        $dompdf->stream('reporte_libro.pdf', ["Attachment" => false]);
        exit;
    }

    // ============================= REPORTES ACTIVOS (VISTA Y GENERACIÓN) =============================

    public function activosView()
    {
        $prestamoModel = new PrestamoAlumnoModel();
        $perPage = $this->request->getGet('per_page') ?? 5;

        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.fecha_devuelto', null)
            ->paginate($perPage);

        $pager = $prestamoModel->pager;

        return view('Administrador/Reportes/activo', [
            'prestamos' => $prestamos,
            'pager' => $pager,
            'perPage' => $perPage
        ]);
    }

    /**
     * Genera el PDF de préstamos activos usando Dompdf.
     */
    public function prestamosActivos()
    {
        $prestamoModel = new PrestamoAlumnoModel();
        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.fecha_devuelto', null)
            ->findAll();

        $html = $this->getReporteHeader('Reporte de Préstamos Activos');
        
        $html .= '<table>
                     <thead>
                     <tr><th>Alumno</th><th>Libro</th><th>Copia</th><th>Préstamo</th><th>Devolución Esperada</th><th>Estado</th></tr>
                     </thead>
                     <tbody>';

        if (empty($prestamos)) {
             $html .= '<tr><td colspan="6" class="text-center">No hay préstamos activos registrados.</td></tr>';
        } else {
            foreach ($prestamos as $p) {
                $estadoFormateado = $this->formatEstado($p['estado']);
                 // Dompdf usa el CSS :nth-child(even), no necesitamos bgcolor ni $i++
                $html .= "<tr>
                                <td>" . esc($p['alumno']) . "</td>
                                <td>" . esc($p['titulo']) . "</td>
                                <td class='text-center'>" . esc($p['no_copia']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_prestamo']) . "</td>
                                <td class='text-center'>" . esc($p['fecha_de_devolucion']) . "</td>
                                <td class='text-center'>{$estadoFormateado}</td>
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