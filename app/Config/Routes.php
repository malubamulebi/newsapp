<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// UI
$routes->get('/', 'Home::index');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::doLogin');
$routes->get('logout', 'AuthController::logout');

// Admin UI (form to create a post)
$routes->get('new-post', 'AdminUiController::newPost');   // shows the form

// Posts
$routes->get('posts', 'PostsController::index');
$routes->get('posts/(:num)', 'PostsController::show/$1');
$routes->post('create_post', 'PostsController::create');  // handles submit
$routes->post('update_post/(:num)', 'PostsController::update/$1');
$routes->delete('delete_post/(:num)', 'PostsController::delete/$1');

// optional: implemented PostsController::view($id)
$routes->get('posts/view/(:num)', 'PostsController::view/$1');

// Users 
// $routes->get('users', 'UsersController::index');
// $routes->get('users/(:num)', 'UsersController::show/$1');
// $routes->post('create_user', 'UsersController::create');
// $routes->post('update_user/(:num)', 'UsersController::update/$1');
// $routes->delete('delete_user/(:num)', 'UsersController::delete/$1');
