<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('panel', 'Login::panel');
$routes->get('login/salir', 'Login::salir');

$routes->get('libros','Libros::index' );
$routes->get('libros/new','Libros::new' );
$routes->post('libros/create', 'Libros::create');
$routes->get('libros/edit/(:num)', 'Libros::edit/$1');
$routes->post('libros/update/(:num)', 'Libros::update/$1');
$routes->get('libros/delete/(:num)', 'Libros::delete/$1');

$routes->get('administrador/panel', 'Administrador::panel');
$routes->get('bibliotecario/panel', 'Bibliotecario::panel');
$routes->get('alumno/panel', 'Alumno::panel');

$routes->get('categorias', 'Categorias::index');
$routes->get('categorias/create', 'Categorias::create');
$routes->post('categorias/store', 'Categorias::store');
$routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
$routes->post('categorias/update/(:num)', 'Categorias::update/$1');
$routes->get('categorias/delete/(:num)', 'Categorias::delete/$1');

$routes->get('usuarios', 'Usuarios::index');
$routes->get('usuarios/create', 'Usuarios::create');
$routes->post('usuarios/store', 'Usuarios::store');
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->get('usuarios/delete/(:num)', 'Usuarios::delete/$1');

$routes->group('transacciones', ['namespace' => 'App\Controllers'], function($routes) {
    
    // Listado de transacciones
    $routes->get('/', 'Transacciones::index');

    // Crear nueva transacción
    $routes->get('create', 'Transacciones::create');
    $routes->post('store', 'Transacciones::store');

    // Editar transacción
    $routes->get('edit/(:num)', 'Transacciones::edit/$1');
    $routes->post('update/(:num)', 'Transacciones::update/$1');

    // Eliminar transacción
    $routes->get('delete/(:num)', 'Transacciones::delete/$1');

});


$routes->get('reportes', 'Reportes::index');
$routes->post('reportes/generar', 'Reportes::generar');
