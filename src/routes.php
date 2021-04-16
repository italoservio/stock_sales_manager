<?php
namespace App;

use Slim\Routing\RouteCollectorProxy;

use \App\Controllers\userController;
use \App\Controllers\productController;
use \App\Controllers\orderController;
use \App\Controllers\categoryController;
use \App\Controllers\indexController;

$app->get('/', userController::class . ':getUsers');

$app->group('/users', function (RouteCollectorProxy $group) {
	$group->get('/create', userController::class . ':createUser');
});