<?php

namespace App\Controllers;

class Alumno extends BaseController
{
    public function panel()
    {
        return view('Alumno/panel'); // Muestra la view de la página principal del alumno/maestro.
    }
}
