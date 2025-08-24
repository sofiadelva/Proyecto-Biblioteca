<?php

namespace App\Controllers;

class Bibliotecario extends BaseController
{
    public function panel()
    {
        return view('Bibliotecario/panel'); // 👈 app/Views/Bibliotecario/panel.php
    }
}
