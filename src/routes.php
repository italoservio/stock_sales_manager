<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use \App\Controllers\userController;
use \App\Controllers\productController;
use \App\Controllers\orderController;
use \App\Controllers\categoryController;
use \App\Controllers\indexController;

$app->get('/', userController::class . ':getUsers');