<?php
namespace App\Models;
use CodeIgniter\Model;
class UsuarioModel extends Model
{

protected $table = 'usuarios';
protected $primaryKey = 'usuario_id';
protected $allowedFields = ['carne', 'password'];
public function verificarUsuario($usuario, $password)
{
return $this->where('carne', $usuario)
->where('password', md5($password))

->first();

}
}