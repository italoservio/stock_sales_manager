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
$app->get('/admin', UserController::class . ':admin');

$app->group('/products', function (RouteCollectorProxy $group) {
	$group->get('[/]', ProductController::class . ':getAll');
});

$app->group('/users', function (RouteCollectorProxy $group) {
	$group->get('/auth', UserController::class . ':authenticate');
	$group->post('/create', UserController::class . ':create');
});
