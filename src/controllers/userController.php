<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Database;
use \App\Entities\User;
use \App\Entities\Product;
use \App\Services\Helper;

class userController {

	public function login(Request $req, Response $res, $args) : Response {
		return Helper::render('login', 'Login', $req, $res);
	}

	public function getUsers(Request $req, Response $res, $args) : Response {

		// Exemplo usando associação do banco de dados:

		$em = Database::manager();
		$productRepository = $em->getRepository(Product::class);
		$Product = $productRepository->find(1);

		$arr = [
			'id' => $Product->getId(),
			'name' => $Product->getName(),
			'descricao' => $Product->getDesc(),
			'categoria' => $Product->getCategory()->getName()
		];

		$res->getBody()->write(json_encode($arr));
		return $res;
	}

	public function createUser(Request $req, Response $res, $args) : Response {

		// Exemplo de criação de usuário:

		$em = Database::manager();
		$User = new User();
		$User->setName('Matheus Henrique 5');
		$User->setLogin('matts');
		$User->setPass('123');
		$User->setEmail('matheus@gmail.com');
		$User->setAdmin(0);
		$em->persist($User);
		$em->flush();

		$arr = [
			'id' => $User->getId(),
			'login' => $User->getLogin()
		];

		$res->getBody()->write(json_encode($arr));
		return $res;
	}

}