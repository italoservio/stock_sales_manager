<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use \App\Services\Helper;

class indexController {

	public function index(Request $req, Response $res, $args) {
		$twig = Twig::fromRequest($req);
		return $twig->render($res, 'index.twig', [
			'assetsPath' => $_ENV['BASE_PATH'] . $_ENV['ASSETS_PATH'],
			'title' => 'Teste'
		]);
	}

}