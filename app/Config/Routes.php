<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth Routes
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Admin Only - Users
    $routes->group('users', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('get/(:num)', 'Users::getById/$1');
        $routes->get('data', 'Users::getData');
        $routes->post('store', 'Users::store');
        $routes->post('update/(:num)', 'Users::update/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });

    // Admin Only - Sekolah
    $routes->group('sekolah', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('/', 'Sekolah::index');
        $routes->get('get/(:num)', 'Sekolah::getById/$1');
        $routes->get('data', 'Sekolah::getData');
        $routes->post('store', 'Sekolah::store');
        $routes->post('update/(:num)', 'Sekolah::update/$1');
        $routes->get('delete/(:num)', 'Sekolah::delete/$1');
    });

    // Admin Only - SPPG
    $routes->group('sppg', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('/', 'Sppg::index');
        $routes->get('get/(:num)', 'Sppg::getById/$1');
        $routes->get('data', 'Sppg::getData');
        $routes->post('store', 'Sppg::store');
        $routes->post('update/(:num)', 'Sppg::update/$1');
        $routes->get('delete/(:num)', 'Sppg::delete/$1');
    });

    // Menu Harian (Admin & SPPG)
    $routes->group('menu', static function ($routes) {
        $routes->get('/', 'Menu::index');
        $routes->get('get/(:num)', 'Menu::getById/$1');
        $routes->get('data', 'Menu::getData');
        $routes->post('store', 'Menu::store');
        $routes->post('update/(:num)', 'Menu::update/$1');
        $routes->get('delete/(:num)', 'Menu::delete/$1');
    });

    // Distribusi (Admin & SPPG)
    $routes->group('distribusi', static function ($routes) {
        $routes->get('/', 'Distribusi::index');
        $routes->get('get/(:num)', 'Distribusi::getById/$1');
        $routes->get('data', 'Distribusi::getData');
        $routes->post('store', 'Distribusi::store');
        $routes->post('update/(:num)', 'Distribusi::update/$1');
        $routes->get('delete/(:num)', 'Distribusi::delete/$1');
        $routes->post('getMenuBySppg', 'Distribusi::getMenuBySppg');
    });
});
