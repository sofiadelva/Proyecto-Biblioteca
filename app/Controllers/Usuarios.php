<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Usuarios extends Controller
{
    protected $usuarioModel;

    public function __construct()
    {
        // Inicializa el modelo de usuario y helpers
        $this->usuarioModel = new UsuarioModel();
        helper(['form', 'url']);
    }

    /**
     * Muestra la lista de usuarios con filtros, búsqueda y paginación.
     */
    public function index()
    {
        $defaultPerPage = 10;
        
        // 1. Obtener parámetros GET para filtros y ordenación
        $ordenar = $this->request->getGet('ordenar');
        $buscar = $this->request->getGet('buscar'); 
        $rolFiltro = $this->request->getGet('rol'); 
        
        // Obtener y validar $perPage (filas por página)
        $perPage = (int)($this->request->getGet('per_page') ?? $defaultPerPage); 

        // Aseguramos que $perPage sea al menos 1
        if ($perPage < 1) {
            $perPage = $defaultPerPage; 
        }

        // Crear el constructor de consultas usando el modelo
        $builder = $this->usuarioModel;

        // 2. Aplicar BÚSQUEDA por nombre, correo o carne (Corregido: usa 'correo' y 'carne')
        if ($buscar) {
            $builder = $builder->groupStart()
                               ->like('nombre', $buscar, 'both')
                               ->orLike('correo', $buscar, 'both') 
                               ->orLike('carne', $buscar, 'both') 
                               ->groupEnd();
        }

        // 3. Aplicar FILTRO por rol
        if ($rolFiltro) {
            $builder = $builder->where('rol', $rolFiltro);
        }

        // 4. Aplicar ORDENACIÓN
        if ($ordenar) {
            switch ($ordenar) {
                case 'nombre_asc':
                    $builder = $builder->orderBy('nombre', 'ASC');
                    break;
                case 'nombre_desc':
                    $builder = $builder->orderBy('nombre', 'DESC');
                    break;
                case 'reciente':
                    $builder = $builder->orderBy('usuario_id', 'DESC');
                    break;
                case 'correo_asc': 
                    $builder = $builder->orderBy('correo', 'ASC');
                    break;
            }
        }
        
        // 5. Aplicar paginación
        $data['usuarios'] = $builder->paginate($perPage, 'default');
        
        // 6. Preparar datos para la vista
        $data['pager'] = $this->usuarioModel->pager;
        $data['perPage'] = $perPage;
        $data['buscar'] = $buscar; 
        $data['rolFiltro'] = $rolFiltro;

        // CORREGIDO: Definir los roles disponibles para el filtro de la vista
        $data['rolesDisponibles'] = ['Administrador', 'Bibliotecario', 'Alumno']; 

        return view('Administrador/usuarios', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // Se puede pasar una lista de roles para el formulario de creación si es necesario
        return view('Administrador/Usuarios/nuevo');
    }

    /**
     * Guarda el nuevo usuario en la base de datos.
     */
    public function store()
    {
        $data = $this->request->getPost();
        // Cifra la contraseña antes de guardarla (usando md5 como en tu modelo)
        $data['password'] = md5($data['password']); 
        $this->usuarioModel->insert($data);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario agregado correctamente.');
    }

    /**
     * Muestra el formulario de edición de un usuario específico.
     * @param int $id ID del usuario a editar.
     */
    public function edit($id)
    {
        $data['usuario'] = $this->usuarioModel->find($id);
        // Se pueden pasar los roles para que el usuario pueda cambiarlo
        return view('Administrador/Usuarios/edit', $data);
    }

    /**
     * Actualiza los datos de un usuario en la base de datos.
     * @param int $id ID del usuario a actualizar.
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        
        // Solo actualiza la contraseña si se proporcionó una nueva
        if (!empty($data['password'])) {
            $data['password'] = md5($data['password']);
        } else {
            // Si el campo de contraseña está vacío, lo eliminamos para no sobreescribir la existente con un hash vacío
            unset($data['password']);
        }
        
        $this->usuarioModel->update($id, $data);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     * @param int $id ID del usuario a eliminar.
     */
    public function delete($id)
    {
        $this->usuarioModel->delete($id);
        return redirect()->to(base_url('usuarios'))->with('msg', 'Usuario eliminado correctamente.');
    }
}