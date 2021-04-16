<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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


// $app->get('/', userController::class . ':getUsers');

// $app->get('/users[/]', userController::class . ':createUser');