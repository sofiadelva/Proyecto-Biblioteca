<?php

namespace App\Controllers;

use TCPDF;

class Reportes extends BaseController
{
    public function index()
    {
        // Carga la vista que tiene el formulario
        return view('Administrador/reportes');
    }

    public function generar()
    {
        // Crear instancia TCPDF
        $pdf = new TCPDF();

        // Configuraciones básicas del PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Tu Nombre o Empresa');
        $pdf->SetTitle('Reporte de Libros');
        $pdf->SetSubject('Listado de libros');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Contenido HTML para el PDF (puedes traerlo de tu base de datos)
        $html = '<h1>Reporte de Libros</h1>';
        $html .= '<table border="1" cellpadding="4">
                    <thead>
                        <tr>
                            <th><b>Título</b></th>
                            <th><b>Autor</b></th>
                            <th><b>Editorial</b></th>
                            <th><b>Cantidad Total</b></th>
                            <th><b>Estado</b></th>
                        </tr>
                    </thead>
                    <tbody>';

        // Simulación de datos, remplaza por datos reales de modelo
        $libros = [
            ['titulo' => 'El maravilloso mago de Oz', 'autor' => 'Baum, L Frank', 'editorial' => 'Editorial 3', 'cantidad' => 1, 'estado' => 'Disponible'],
            ['titulo' => 'Piel de león...', 'autor' => 'Del Amo, Monserrat', 'editorial' => 'Editorial 9', 'cantidad' => 1, 'estado' => 'Dañado'],
        ];

        foreach ($libros as $libro) {
            $html .= '<tr>
                <td>'.esc($libro['titulo']).'</td>
                <td>'.esc($libro['autor']).'</td>
                <td>'.esc($libro['editorial']).'</td>
                <td>'.esc($libro['cantidad']).'</td>
                <td>'.esc($libro['estado']).'</td>
                </tr>';
        }

        $html .= '</tbody></table>';

        // Escribir HTML al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Enviar PDF al navegador (vista inline)
        $pdf->Output('reporte_libros.pdf', 'I');

        // Interrumpir para evitar salida posterior que dañe PDF
        exit;
    }
}
