<?php
namespace App;

use Slim\Routing\RouteCollectorProxy;

use \App\Controllers\UserController;
use \App\Controllers\ProductController;
use \App\Controllers\OrdersController;
use \App\Controllers\CategoryController;
use \App\Controllers\IndexController;

$app->get('/', IndexController::class . ':index');
$app->get('/login', UserController::class . ':login');
$app->get('/signup', UserController::class . ':signup');
$app->get('/logout', UserController::class . ':logout');
$app->get('/cart', OrdersController::class . ':cart');

$app->group('/categories', function(RouteCollectorProxy $group) {
	$group->get('[/]', CategoryController::class . ':getAll');
  $group->get('/get', CategoryController::class . ':get');
	$group->post('/create', CategoryController::class . ':create');
	$group->delete('/delete/{id}', CategoryController::class . ':delete');
});

$app->group('/products', function(RouteCollectorProxy $group) {
	$group->get('[/]', ProductController::class . ':getAll');
  $group->post('/create', ProductController::class . ':create');
  $group->delete('/delete/{id}', ProductController::class . ':delete');
  $group->get('/{id}', ProductController::class . ':get');
  $group->get('/{id}/details', ProductController::class . ':details');
});

$app->group('/users', function(RouteCollectorProxy $group) {
	$group->get('[/]', UserController::class . ':getAll');
	$group->get('/auth', UserController::class . ':authenticate');
	$group->post('/create', UserController::class . ':create');
  $group->get('/orders', UserController::class . ':orders');
	$group->delete('/delete/{id}', UserController::class . ':delete');
  $group->get('/orders/all', OrdersController::class . ':getByUserId');
	$group->post('/{id}/image', UserController::class . ':changeImage');
	$group->get('/{id}', UserController::class . ':profile');
});

$app->group('/admin', function(RouteCollectorProxy $group) {
	$group->get('[/]', IndexController::class . ':admin');
	$group->get('/users', UserController::class . ':adminUsers');
	$group->get('/orders', OrdersController::class . ':adminOrders');
	$group->get('/products', ProductController::class . ':adminProducts');
	$group->get('/statistics', IndexController::class . ':adminStatistics');
	$group->get('/categories', CategoryController::class . ':adminCategories');
});

$app->group('/orders', function(RouteCollectorProxy $group) {
  $group->get('/all', OrdersController::class . ':getAll');
  $group->post('/payed', OrdersController::class . ':setPayed');
  $group->post('/set', OrdersController::class . ':set');
  $group->get('/boleto', OrdersController::class . ':getBoleto');
  $group->get('/{id}/all', OrdersController::class . ':getById');
  $group->get('/{id}', OrdersController::class . ':get');
});