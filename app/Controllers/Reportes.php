<?php

namespace App\Controllers;

use TCPDF;
use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\PrestamoAlumnoModel;

class Reportes extends BaseController
{
    // INDEX con botones
    public function index()
    {
        return view('Administrador/Reportes/index');
    }

    // =========================================================================
    // REPORTE POR ALUMNO (alumnoView y alumno)
    // =========================================================================

    public function alumnoView()
    {
        $usuarioModel = new UsuarioModel();
        // Obtener solo a los alumnos para el datalist
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

    public function alumno()
    {
        $nombreAlumno = $this->request->getPost('usuario_nombre');

        $usuarioModel = new UsuarioModel();
        $alumno = $usuarioModel->where('nombre', $nombreAlumno)->first();

        if (!$alumno && $nombreAlumno) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        $prestamoModel = new PrestamoAlumnoModel();
        
        $query = $prestamoModel
            ->select('libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado');
        
        // Aplicar JOINs aquí también para la generación del PDF
        $query->join('libros', 'libros.libro_id = prestamos.libro_id')
              ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id');
        
        // Aplicar filtro si el alumno fue encontrado
        if ($alumno) {
            $query->where('prestamos.usuario_id', $alumno['usuario_id']);
        }
        
        $prestamos = $query->findAll();


        $pdf = new TCPDF();
        $pdf->AddPage();

        $titulo = 'Reporte de Préstamos';
        if($alumno) {
             $titulo .= ' del Alumno: ' . esc($alumno['nombre']);
        } else {
             $titulo .= ' (Todos los Alumnos)';
        }

        $html = '<h2>' . $titulo . '</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <tr><th>Título</th><th>No. Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr>';

        foreach ($prestamos as $p) {
            $html .= "<tr>
                        <td>{$p['titulo']}</td>
                        <td>{$p['no_copia']}</td>
                        <td>{$p['fecha_prestamo']}</td>
                        <td>{$p['fecha_de_devolucion']}</td>
                        <td>" . ($p['fecha_devuelto'] ?? '-') . "</td>
                        <td>{$p['estado']}</td>
                    </tr>";
        }
        $html .= '</table>';

        $pdf->writeHTML($html);
        $pdf->Output('reporte_alumno.pdf', 'I');
        exit;
    }

    // =========================================================================
    // REPORTE POR LIBRO (libroView y libro)
    // =========================================================================

    public function libroView()
    {
        $libroModel = new LibroModel();
        $libros = $libroModel->findAll(); // Para la datalist

        $perPage = $this->request->getGet('per_page') ?? 5;
        $tituloLibro = $this->request->getGet('libro_titulo');

        $prestamoModel = new PrestamoAlumnoModel();
        
        // Asegurarse de seleccionar 'libros.titulo'
        $query = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id') // Necesitas este JOIN
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
    
    // ⭐️ FUNCIÓN FALTANTE IMPLEMENTADA PARA PDF ⭐️
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

        // --- Generación del PDF ---
        $pdf = new TCPDF();
        $pdf->AddPage();

        $titulo = 'Reporte Histórico de Préstamos';
        if ($libro) {
            $titulo .= ' del Libro: ' . esc($libro['titulo']);
        } else {
            $titulo .= ' (Todos los Libros)';
        }

        $html = '<h2>' . $titulo . '</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <tr><th>Alumno</th><th>Título</th><th>No. Copia</th><th>Préstamo</th><th>Devolución</th><th>Devuelto</th><th>Estado</th></tr>';

        foreach ($prestamos as $p) {
            $html .= "<tr>
                        <td>{$p['alumno']}</td>
                        <td>{$p['titulo']}</td>
                        <td>{$p['no_copia']}</td>
                        <td>{$p['fecha_prestamo']}</td>
                        <td>{$p['fecha_de_devolucion']}</td>
                        <td>" . ($p['fecha_devuelto'] ?? '-') . "</td>
                        <td>{$p['estado']}</td>
                    </tr>";
        }
        $html .= '</table>';

        $pdf->writeHTML($html);
        $pdf->Output('reporte_libro.pdf', 'I');
        exit;
    }


    // =========================================================================
    // REPORTE PRÉSTAMOS ACTIVOS (activosView y prestamosActivos)
    // =========================================================================

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

        $pdf = new TCPDF();
        $pdf->AddPage();

        $html = '<h2>Reporte de Préstamos Activos</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <tr><th>Alumno</th><th>Libro</th><th>No. Copia</th><th>Préstamo</th><th>Devolución</th><th>Estado</th></tr>';

        foreach ($prestamos as $p) {
            $html .= "<tr>
                        <td>{$p['alumno']}</td>
                        <td>{$p['titulo']}</td>
                        <td>{$p['no_copia']}</td>
                        <td>{$p['fecha_prestamo']}</td>
                        <td>{$p['fecha_de_devolucion']}</td>
                        <td>{$p['estado']}</td>
                    </tr>";
        }
        $html .= '</table>';

        $pdf->writeHTML($html);
        $pdf->Output('reporte_activos.pdf', 'I');
        exit;
    }

    // =========================================================================
    // REPORTE LIBROS DISPONIBLES (disponiblesView y librosDisponibles)
    // =========================================================================

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

    public function librosDisponibles()
    {
        $libroModel = new LibroModel();
        $libros = $libroModel->where('estado', 'Disponible')->findAll();

        $pdf = new TCPDF();
        $pdf->AddPage();

        $html = '<h2>Reporte de Libros Disponibles</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <tr><th>Título</th><th>Autor</th><th>Editorial</th><th>Cantidad</th></tr>';

        foreach ($libros as $l) {
            $html .= "<tr>
                        <td>{$l['titulo']}</td>
                        <td>{$l['autor']}</td>
                        <td>{$l['editorial']}</td>
                        <td>{$l['cantidad_disponibles']}</td>
                    </tr>";
        }
        $html .= '</table>';

        $pdf->writeHTML($html);
        $pdf->Output('reporte_disponibles.pdf', 'I');
        exit;
    }
}