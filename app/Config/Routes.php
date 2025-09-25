<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/**Rutas para el inicio de sesión. */
$routes->get('/', 'Home::index');
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('panel', 'Login::panel');
$routes->get('login/salir', 'Login::salir');

/**Rutas para CRUD de libros (administrador). */
$routes->get('libros','Libros::index' );
$routes->get('libros/new','Libros::new' );
$routes->post('libros/create', 'Libros::create');
$routes->get('libros/edit/(:num)', 'Libros::edit/$1');
$routes->post('libros/update/(:num)', 'Libros::update/$1');
$routes->get('libros/delete/(:num)', 'Libros::delete/$1');

/**Rutas para página principal de los tres usuarios. */
$routes->get('administrador/panel', 'Administrador::panel');
$routes->get('bibliotecario/panel', 'Bibliotecario::panel');
$routes->get('alumno/panel', 'Alumno::panel');

/**Rutas para CRUD de categorías (administrador) */
$routes->get('categorias', 'Categorias::index');
$routes->get('categorias/create', 'Categorias::create');
$routes->post('categorias/store', 'Categorias::store');
$routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
$routes->post('categorias/update/(:num)', 'Categorias::update/$1');
$routes->get('categorias/delete/(:num)', 'Categorias::delete/$1');

/**Rutas para CRUD de usuarios (administrador) */
$routes->get('usuarios', 'Usuarios::index');
$routes->get('usuarios/create', 'Usuarios::create');
$routes->post('usuarios/store', 'Usuarios::store');
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->get('usuarios/delete/(:num)', 'Usuarios::delete/$1');

/**Rutas para CRUD de transacciones (administrador) */
$routes->group('transacciones', ['namespace' => 'App\Controllers'], function($routes) {
    
    $routes->get('/', 'Transacciones::index');
    $routes->get('create', 'Transacciones::create');
    $routes->post('store', 'Transacciones::store');
    $routes->get('edit/(:num)', 'Transacciones::edit/$1');
    $routes->post('update/(:num)', 'Transacciones::update/$1');
    $routes->get('delete/(:num)', 'Transacciones::delete/$1');

});

/**Rutas para reportes (administrador) */
$routes->get('reportes', 'Reportes::index');
/**Por alumno */
$routes->get('reportes/alumno', 'Reportes::alumnoView');
$routes->post('reportes/alumno/pdf', 'Reportes::alumno');
/**Por libro */
$routes->get('reportes/libro', 'Reportes::libroView');
$routes->post('reportes/libro/pdf', 'Reportes::libro');
/**Préstamos activos*/
$routes->get('reportes/activos', 'Reportes::activosView');
$routes->post('reportes/activos/pdf', 'Reportes::prestamosActivos');
/**Libros disponibles*/
$routes->get('reportes/disponibles', 'Reportes::disponiblesView');
$routes->post('reportes/disponibles/pdf', 'Reportes::librosDisponibles');

/**Rutas para CRUD de ejemplares de libros (administrador) */
$routes->get('ejemplares/listar/(:num)', 'Ejemplares::listar/$1');   
$routes->get('ejemplares/new/(:num)', 'Ejemplares::new/$1');        
$routes->post('ejemplares/create', 'Ejemplares::create');           
$routes->get('ejemplares/edit/(:num)', 'Ejemplares::edit/$1');       
$routes->post('ejemplares/update/(:num)', 'Ejemplares::update/$1');  
$routes->get('ejemplares/delete/(:num)', 'Ejemplares::delete/$1');  

/**Ruta para ver inventario. */
$routes->get('inventario', 'Inventario::index');

/**Rutas para préstamos y devoluciones */
$routes->get('gestion_libros', 'GestionLibros::index');
// Préstamos
$routes->get('prestamos', 'Prestamos::create');   // botón lleva directo al form
$routes->post('prestamos/store', 'Prestamos::store');
$routes->get('prestamos/getEjemplares/(:num)', 'Prestamos::getEjemplares/$1');
// Devoluciones
$routes->get('devoluciones', 'Devoluciones::index');
$routes->post('devoluciones/store', 'Devoluciones::store');

/**Ruta para ver inventario alumno */
$routes->get('alumno/inventario', 'InventarioAlumno::index');

/**Ruta para ver préstamos realiazados por alumno. */
$routes->get('alumno/prestamos', 'PrestamosAlumno::index');
