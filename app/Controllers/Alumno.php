<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Alumno extends BaseController
{
    /**
     * Muestra la página principal (Home) del Alumno/Maestro.
     * Esta vista debe ser simple y no requerir variables de filtro o modelos de datos complejos.
     */
    public function panel()
    {
        // La vista 'Alumno/panel' se llama sin pasarle ninguna variable ($categorias, $perPage, etc.)
        // Si la vista aún da error, deberás eliminar el bloque de filtrado de inventario 
        // DENTRO del archivo 'Alumno/panel.php'.
        return view('Alumno/panel'); 
    }
}
