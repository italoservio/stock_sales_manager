<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require './controllers/categoryController.php';
require './controllers/orderController.php';
require './controllers/productController.php';
require './controllers/userController.php';

$app->get('/', 'userController:getUsers');