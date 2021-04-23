<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use Exception;
use \App\Entities\User;
use \App\Entities\Client;
use \App\Entities\Product;

class UserController {

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

	public function signup(Request $req, Response $res, $args) : Response {
		return Helper::render('signup', $req, $res);
	}

	public function adminUsers(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			return Helper::render('adminUsers', $req, $res);
		} else {
			return Helper::render('login', $req, $res);
		}
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

	public function create(Request $req, Response $res, $args) : Response {

		$data = $req->getParsedBody();
		$em = Database::manager();

		try {

			$em->getConnection()->beginTransaction();

			// Creating user:
			$User = new User();
			$User->setLogin($data['user']);
			$User->setPass(md5($data['pass']));
			$User->setName($data['name']);
			$User->setEmail($data['email']);
			$User->setAdmin(0);
			$em->persist($User);

			// Creating Client:
			$Client = new Client();
			$Client->setCep($data['cep']);
			$Client->setCidade($data['cidade']);
			$Client->setBairro($data['estado']);
			$Client->setLogradouro($data['bairro']);
			$Client->setNumero($data['numero']);
			$Client->setComplemento($data['complemento']);
			$Client->setLogradouro($data['logradouro']);
			$Client->setUserId($User->getId());
			$Client->setUser($User);
			$em->persist($Client);

			$em->flush();
			$em->getConnection()->commit();

			// Starting session:
			Auth::setSession($User);

			$arr = [
				'status' => true
			];

		} catch (Exception $th) {
			$em->getConnection()->rollBack();
			$arr = [
				'status' => false,
				'message' => 'Ocorreu um erro ao criar o usuário',
				'error' => $th->getMessage()
			];
		}

		$res->getBody()->write(json_encode($arr));
		return $res;
	}

}