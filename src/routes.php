<?php
namespace App;

use Slim\Routing\RouteCollectorProxy;

use \App\Controllers\UserController;
use \App\Controllers\ProductController;
use \App\Controllers\OrderController;
use \App\Controllers\CategoryController;
use \App\Controllers\IndexController;

$app->get('/', IndexController::class . ':index');
$app->get('/login', UserController::class . ':login');
$app->get('/signup', UserController::class . ':signup');
$app->get('/logout', UserController::class . ':logout');

$app->group('/categories', function(RouteCollectorProxy $group) {
	$group->get('[/]', CategoryController::class . ':getAll');
	$group->post('/create', CategoryController::class . ':create');
	$group->delete('/delete/{id}', CategoryController::class . ':delete');
});

$app->group('/products', function(RouteCollectorProxy $group) {
	$group->get('[/]', ProductController::class . ':getAll');
  $group->get('/{id}', ProductController::class . ':details');
});

$app->group('/users', function(RouteCollectorProxy $group) {
	$group->get('[/]', UserController::class . ':getAll');
	$group->get('/auth', UserController::class . ':authenticate');
	$group->post('/create', UserController::class . ':create');
});

$app->group('/admin', function(RouteCollectorProxy $group) {
	$group->get('[/]', IndexController::class . ':admin');
	$group->get('/users', UserController::class . ':adminUsers');
	$group->get('/orders', OrderController::class . ':adminOrders');
	$group->get('/products', ProductController::class . ':adminProducts');
	$group->get('/statistics', IndexController::class . ':adminStatistics');
	$group->get('/categories', CategoryController::class . ':adminCategories');
});