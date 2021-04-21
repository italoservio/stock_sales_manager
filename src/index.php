<?php
namespace App;

use Symfony\Component\Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

// Loading DotEnv
(new Dotenv())->load(__DIR__ . '/../.env');

// Creating Slim App
$app = AppFactory::create();
$app->setBasePath($_ENV['BASE_PATH']);

// Errors enabled
$app->addErrorMiddleware(true, true, true);

// Setting view render
$twig = Twig::create(__DIR__ . '/../public/pages');
$app->add(TwigMiddleware::create($app, $twig));

require 'routes.php';

$app->run();

