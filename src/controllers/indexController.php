<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;


class indexController {

	public function index(Request $req, Response $res, $args) {
		return Helper::render('index', 'Página Inicial', $req, $res);
	}

}