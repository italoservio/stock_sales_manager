<?php
namespace App;

use Slim\Routing\RouteCollectorProxy;

use \App\Controllers\userController;
use \App\Controllers\productController;
use \App\Controllers\orderController;
use \App\Controllers\categoryController;
use \App\Controllers\indexController;

$app->get('/', indexController::class . ':index');
$app->get('/login', userController::class . ':login');
$app->get('/signup', userController::class . ':signup');
$app->get('/logout', userController::class . ':logout');
$app->get('/admin', userController::class . ':admin');

$app->group('/users', function (RouteCollectorProxy $group) {
	$group->get('/auth', userController::class . ':authenticate');
	$group->get('/create', userController::class . ':create');
});