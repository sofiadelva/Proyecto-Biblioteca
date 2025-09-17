<?php

namespace App\Controllers;

use TCPDF;
use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\PrestamoAlumnoModel;

class Reportes extends BaseController
{
    public function index()
    {
        // Traer lista de alumnos y libros para el datalist
        $usuarioModel = new UsuarioModel();
        $libroModel = new LibroModel();

        $alumnos = $usuarioModel->where('rol', 'Alumno')->findAll();
        $libros = $libroModel->findAll();

        $data = [
            'alumnos' => $alumnos,
            'libros' => $libros
        ];

        return view('Administrador/reportes', $data);
    }

    // Reporte por Alumno
    public function alumno()
    {
        $nombreAlumno = $this->request->getPost('usuario_nombre');

        $usuarioModel = new UsuarioModel();
        $alumno = $usuarioModel->where('nombre', $nombreAlumno)->first();

        if (!$alumno) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        $prestamoModel = new PrestamoAlumnoModel();
        $prestamos = $prestamoModel
            ->select('libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.usuario_id', $alumno['usuario_id'])
            ->orderBy('prestamos.fecha_prestamo', 'DESC')
            ->findAll();

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EverBook');
        $pdf->SetTitle('Reporte de Préstamos por Alumno');
        $pdf->AddPage();

        $html = '<h2>Reporte de Préstamos del Alumno: ' . esc($alumno['nombre']) . '</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>No. Copia</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Fecha Devuelto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($prestamos as $p) {
            $html .= '<tr>
                        <td>' . esc($p['titulo']) . '</td>
                        <td>' . esc($p['no_copia']) . '</td>
                        <td>' . esc($p['fecha_prestamo']) . '</td>
                        <td>' . esc($p['fecha_de_devolucion']) . '</td>
                        <td>' . esc($p['fecha_devuelto'] ?? '-') . '</td>
                        <td>' . esc($p['estado']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_alumno.pdf', 'I');
        exit;
    }

    // Reporte por Libro
    public function libro()
    {
        $tituloLibro = $this->request->getPost('libro_titulo');

        $libroModel = new LibroModel();
        $libro = $libroModel->where('titulo', $tituloLibro)->first();

        if (!$libro) {
            return redirect()->back()->with('error', 'Libro no encontrado.');
        }

        $prestamoModel = new PrestamoAlumnoModel();
        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.fecha_devuelto, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.libro_id', $libro['libro_id'])
            ->orderBy('prestamos.fecha_prestamo', 'DESC')
            ->findAll();

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EverBook');
        $pdf->SetTitle('Reporte de Préstamos por Libro');
        $pdf->AddPage();

        $html = '<h2>Reporte de Préstamos del Libro: ' . esc($libro['titulo']) . '</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>No. Copia</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Fecha Devuelto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($prestamos as $p) {
            $html .= '<tr>
                        <td>' . esc($p['alumno']) . '</td>
                        <td>' . esc($p['no_copia']) . '</td>
                        <td>' . esc($p['fecha_prestamo']) . '</td>
                        <td>' . esc($p['fecha_de_devolucion']) . '</td>
                        <td>' . esc($p['fecha_devuelto'] ?? '-') . '</td>
                        <td>' . esc($p['estado']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_libro.pdf', 'I');
        exit;
    }

    // Reporte de Préstamos Activos
    public function prestamosActivos()
    {
        $prestamoModel = new PrestamoAlumnoModel();
        $prestamos = $prestamoModel
            ->select('usuarios.nombre as alumno, libros.titulo, ejemplares.no_copia, prestamos.fecha_prestamo, prestamos.fecha_de_devolucion, prestamos.estado')
            ->join('usuarios', 'usuarios.usuario_id = prestamos.usuario_id')
            ->join('libros', 'libros.libro_id = prestamos.libro_id')
            ->join('ejemplares', 'ejemplares.ejemplar_id = prestamos.ejemplar_id')
            ->where('prestamos.fecha_devuelto', null)
            ->orderBy('prestamos.fecha_prestamo', 'DESC')
            ->findAll();

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EverBook');
        $pdf->SetTitle('Reporte de Préstamos Activos');
        $pdf->AddPage();

        $html = '<h2>Reporte de Préstamos Activos</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Libro</th>
                            <th>No. Copia</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($prestamos as $p) {
            $html .= '<tr>
                        <td>' . esc($p['alumno']) . '</td>
                        <td>' . esc($p['titulo']) . '</td>
                        <td>' . esc($p['no_copia']) . '</td>
                        <td>' . esc($p['fecha_prestamo']) . '</td>
                        <td>' . esc($p['fecha_de_devolucion']) . '</td>
                        <td>' . esc($p['estado']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_prestamos_activos.pdf', 'I');
        exit;
    }

    // Reporte de Libros Disponibles (se mantiene igual)
    public function librosDisponibles()
    {
        $libroModel = new LibroModel();
        $libros = $libroModel->where('estado', 'Disponible')->findAll();

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('EverBook');
        $pdf->SetTitle('Reporte de Libros Disponibles');
        $pdf->AddPage();

        $html = '<h2>Reporte de Libros Disponibles</h2>';
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Cantidad Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($libros as $l) {
            $html .= '<tr>
                        <td>' . esc($l['titulo']) . '</td>
                        <td>' . esc($l['autor']) . '</td>
                        <td>' . esc($l['editorial']) . '</td>
                        <td>' . esc($l['cantidad_disponibles']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_libros_disponibles.pdf', 'I');
        exit;
    }
}
