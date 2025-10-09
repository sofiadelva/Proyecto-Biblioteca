<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    protected $allowedFields = ['nombre', 'password', 'carne', 'correo', 'rol'];

    // Función para verificar usuario
    public function verificarUsuario($usuario, $password)
    {
        return $this->where('carne', $usuario)
                    ->where('password', md5($password))
                    ->first();
    }
}
