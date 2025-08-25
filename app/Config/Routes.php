<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('api', static function ($routes) {
    $routes->post('login', 'API\AuthController::login');
    $routes->get('dashboard', 'API\DashboardController::getDashboardData');

    // Add this new route BEFORE the resource route
    $routes->get('qualifications/suggest-category', 'API\QualificationController::suggestCategory');

     // qualifications creation
    $routes->resource('qualifications', ['controller' => 'API\QualificationController']);
    
});