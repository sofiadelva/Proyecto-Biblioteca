<?php

namespace App\Controllers;

class Administrador extends BaseController
{
    public function panel()
    {
        return view('Administrador/panel'); // Muestra la view de la página principal del administrador.
    }

    public function libros()
    {
        return view('Administrador/Libros/libros');// Carga directamente la vista de libros.
    }
}
