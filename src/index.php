<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv())->load(__DIR__ . '/../.env');

$app = AppFactory::create();
$app->setBasePath($_ENV['BASE_PATH']);

require 'routes.php';

$app->run();