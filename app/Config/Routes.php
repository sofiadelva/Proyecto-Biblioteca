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
