<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/**Rutas para el inicio de sesión. */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('panel', 'Login::panel');
$routes->get('login/salir', 'Login::salir');

/**Rutas para página principal de los tres usuarios. */
$routes->get('administrador/panel', 'Administrador::panel');
$routes->get('alumno/panel', 'Alumno::panel');

$routes->group('libros', function($routes) {
    $routes->get('/', 'Libros::index');
    $routes->get('new', 'Libros::new');
    $routes->post('create', 'Libros::create');
    $routes->get('get_colecciones_json', 'Libros::get_colecciones_json');
    $routes->get('get_subgeneros_json', 'Libros::get_subgeneros_json');
    $routes->get('get_subcategorias_json', 'Libros::get_subcategorias_json');
});
$routes->get('libros/edit/(:num)', 'Libros::edit/$1');
$routes->post('libros/update/(:num)', 'Libros::update/$1');
$routes->get('libros/delete/(:num)', 'Libros::delete/$1');

/**Rutas para CRUD de ejemplares de libros (administrador) */
$routes->get('ejemplares/listar/(:num)', 'Ejemplares::listar/$1'); 
$routes->get('ejemplares/new/(:num)', 'Ejemplares::new/$1');
$routes->post('ejemplares/create', 'Ejemplares::create');
$routes->get('ejemplares/edit/(:num)', 'Ejemplares::edit/$1');
$routes->post('ejemplares/update/(:num)', 'Ejemplares::update/$1'); 
$routes->get('ejemplares/delete/(:num)', 'Ejemplares::delete/$1');

$routes->group('colecciones', ['namespace' => 'App\Controllers'], function($routes) {
    // Rutas principales
    $routes->get('/', 'Colecciones::index');
    $routes->get('create', 'Colecciones::create');
    $routes->post('store', 'Colecciones::store');
    
    // Rutas de Edición de Colección
    $routes->get('edit/(:num)', 'Colecciones::edit/$1');
    $routes->post('update/(:num)', 'Colecciones::update/$1');
    $routes->get('delete/(:num)', 'Colecciones::delete/$1');

    // Rutas para Subgéneros (Nuevos y Edición)
    $routes->get('nuevo_subgenero', 'Colecciones::nuevoSubgenero');
    $routes->post('guardar_subgenero', 'Colecciones::guardarSubgenero');
    $routes->post('update_subgen/(:num)', 'Colecciones::update_subgen/$1'); // <--- Para el Modal
    $routes->get('delete_subgen/(:num)', 'Colecciones::delete_subgen/$1'); // <--- Para borrar individual

    // Rutas para Subcategorías (Nuevas y Edición)
    $routes->get('nueva_subcategoria', 'Colecciones::nuevaSubcategoria');
    $routes->post('guardar_subcategoria', 'Colecciones::guardarSubcategoria');
    $routes->post('update_subcat/(:num)', 'Colecciones::update_subcat/$1'); // <--- Para el Modal
    $routes->get('delete_subcat/(:num)', 'Colecciones::delete_subcat/$1'); // <--- Para borrar individual
    
    // Utilidades
    $routes->get('get_subgeneros/(:num)', 'Colecciones::getSubgeneros/$1'); // AJAX
});


/**Rutas para gestión de libros, préstamos y devoluciones */
$routes->get('gestion_libros', 'GestionLibros::index');
$routes->group('prestamos', function($routes) {
    $routes->get('/', 'Prestamos::create'); 
    // LA RUTA DEL JSON DEBE IR ANTES QUE LA DEL NÚMERO
    $routes->get('getLibrosJson', 'Prestamos::getLibrosJson'); 
    $routes->get('getEjemplares/(:num)', 'Prestamos::getEjemplares/$1');
    $routes->post('store', 'Prestamos::store');
});
$routes->get('devoluciones', 'Devoluciones::index');
$routes->post('devoluciones/store', 'Devoluciones::store');
$routes->get('devoluciones/confirmar/(:num)', 'Devoluciones::confirmar/$1');

/**Rutas para CRUD de transacciones (administrador) */
$routes->group('transacciones', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Transacciones::index');
    $routes->get('edit/(:num)', 'Transacciones::edit/$1');
    $routes->post('update/(:num)', 'Transacciones::update/$1');
    $routes->get('delete/(:num)', 'Transacciones::delete/$1');
});

/**Rutas para CRUD de usuarios (administrador) */
$routes->group('usuarios', function($routes) {
    $routes->get('/', 'Usuarios::index');
    $routes->get('create', 'Usuarios::create');
    $routes->post('store', 'Usuarios::store');
    $routes->get('edit/(:num)', 'Usuarios::edit/$1');
    $routes->post('update/(:num)', 'Usuarios::update/$1');
    $routes->get('delete/(:num)', 'Usuarios::delete/$1');
    $routes->get('getUsuariosJson', 'Usuarios::getUsuariosJson');
});
/**Rutas para reportes (administrador) */
$routes->get('reportes', 'Reportes::index');
$routes->get('reportes/alumno', 'Reportes::alumnoView');
$routes->post('reportes/alumno/pdf', 'Reportes::alumno');
$routes->get('reportes/libro', 'Reportes::libroView');
$routes->post('reportes/libro/pdf', 'Reportes::libro');
$routes->get('reportes/activos', 'Reportes::activosView');
$routes->post('reportes/activos/pdf', 'Reportes::prestamosActivos');
$routes->get('reportes/disponibles', 'Reportes::disponiblesView');
$routes->post('reportes/disponibles/pdf', 'Reportes::librosDisponibles');

/**Ruta para ver inventario alumno */
$routes->get('alumno/inventario', 'InventarioAlumno::index');
$routes->get('alumno/prestamos', 'PrestamosAlumno::index');