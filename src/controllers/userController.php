<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../models/user.php';

class userController {

	public function getUsers(Request $req, Response $res, $args) : Response {
		$User = new User();
		$json = json_encode([
			'status' => $User->getName()
		]);
		$html = file_get_contents(__DIR__ . '/../../public/index.html');
		$res->getBody()->write($html);
		return $res;
	}

}