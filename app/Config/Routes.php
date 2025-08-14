<?php
use App\Controllers\AuthController;
use App\Controllers\Home;

use App\Controllers\PostsController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'NewsAppItemController::goToSource');
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::doLogin');
$routes->get('admin/new-post', 'AdminUiController::newPost');
$routes->get('logout', 'AuthController::logout');
$routes->get('admin', 'AdminDashboardController::index');
$routes->get('admin/archive-post/(:num)', 'AdminDashboardController::archivePost/$1');
$routes->get('admin/restore-post/(:num)', 'AdminDashboardController::restorePost/$1');


// posts
$routes->get('posts', 'PostsController::index');
$routes->get('posts/(:num)', 'PostsController::show/$1');
$routes->post('create_post', [PostsController::class, 'create']);
$routes->post('update_post/(:num)', 'PostsController::update/$1');
$routes->delete('delete_post/(:num)', 'PostsController::delete/$1');


//admin
$routes->get('admins', 'AdminController::index');
$routes->get('admins/(:num)', 'AdminController::show/$1');
// $routes->post('create_admin', [AdminController::class, 'create']);
$routes->post('create_admin', 'AdminController::create');
$routes->post('update_admin/(:num)', 'AdminController::update/$1');
$routes->delete('delete_admin/(:num)', 'AdminController::delete/$1');

//users
$routes->get('users', 'UsersController::index');
$routes->get('users/(:num)', 'UsersController::show/$1');
$routes->post('create_user', 'UsersController::create');
$routes->post('update_user/(:num)', 'UsersController::update/$1');
$routes->delete('delete_user/(:num)', 'UsersController::delete/$1');


