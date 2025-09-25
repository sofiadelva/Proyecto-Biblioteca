<?php

namespace App\Controllers;

class Bibliotecario extends BaseController
{
    public function panel()
    {
        return view('Bibliotecario/panel'); // Muestra la view de la página principal del bibliotecario.
    }
}
