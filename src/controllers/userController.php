<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\Database;
use \App\Entities\User;
use \App\Entities\Product;

class userController {

	// Views:
	public function login(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession()) {
			$User = Auth::getSession();
			if ($User->getAdmin() === 1) {
				return Helper::render('admin', $req, $res);
			} else {
				return Helper::render('index', $req, $res);
			}
		} else {
			return Helper::render('login', $req, $res);
		}
	}

	public function admin(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession()) {
			$User = Auth::getSession();
			if ($User->getAdmin() === 1) {
				return Helper::render('admin', $req, $res);
			} else {
				return Helper::render('index', $req, $res);
			}
		} else {
			return Helper::render('login', $req, $res);
		}
	}

	public function signup(Request $req, Response $res, $args) : Response {
		return Helper::render('signup', $req, $res);
	}

	// Methods:
	public function logout(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession()) {
			Auth::clearSession();
		}
		return Helper::render('login', $req, $res);
	}

	public function authenticate(Request $req, Response $res, $args) : Response {

		$em = Database::manager();
		$arr = $req->getQueryParams();

		$userRepository = $em->getRepository(User::class);
		$User = $userRepository->findOneBy([
			'login' => $arr['user'],
			'pass' => md5($arr['pass'])
		]);

		if (!is_null($User)) {
			Auth::setSession($User);
			$res->getBody()->write(json_encode([
				'status' => true,
				'admin' => $User->getAdmin()
			]));
		}else {
			$res->getBody()->write(json_encode([
				'status' => false,
				'message' => 'Usuário ou senha inválidos'
			]));
		}
		return $res;
	}

	// public function getUsers(Request $req, Response $res, $args) : Response {

	// 	// Exemplo usando associação do banco de dados:
	// 	// $arr = get_object_vars(objdoctrine)
	// 	$em = Database::manager();
	// 	$productRepository = $em->getRepository(Product::class);
	// 	$Product = $productRepository->find(1);

	// 	$arr = [
	// 		'id' => $Product->getId(),
	// 		'name' => $Product->getName(),
	// 		'description' => $Product->getDesc(),
	// 		'category' => $Product->getCategory()->getName()
	// 	];

	// 	$res->getBody()->write(json_encode($arr));
	// 	return $res;
	// }

	public function create(Request $req, Response $res, $args) : Response {

		// Exemplo de criação de usuário:

		$em = Database::manager();
		$User = new User();
		$User->setName('Matheus Henrique 5');
		$User->setLogin('matts');
		$User->setPass(md5('123456'));
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