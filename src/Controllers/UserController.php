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

	public function profile(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession()) {
			return Helper::render('profile', $req, $res);
		} else {
			return Helper::render('profile', $req, $res);
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

	public function getAll(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			$arr = [];
			try {
				$em = Database::manager();
				$userRepository = $em->getRepository(User::class);
				$users = $userRepository->findAll();
				foreach ($users as $value) {
					$arr[] = [
						'id' 		=> $value->getId(),
						'name' 	=> $value->getName(),
						'email' => $value->getEmail(),
						'login' => $value->getLogin(),
						'pass' 	=> $value->getPass(),
						'admin' => $value->getAdmin()
					];
				}
				$arr = [
					'status' => true,
					'users' => $arr
				];
			} catch (Exception $e) {
				$arr = [
					'status' => false,
					'message' => 'Ocorreu um erro ao buscar os usuários no banco',
					'error' => $e->getMessage()
				];
			}
			$res->getBody()->write(json_encode($arr));
			return $res;
		}
	}

	public function delete(Request $req, Response $res, $args) : Response {
		$id = $args['id'];
    $arr = [];
    try {
      $em = Database::manager();
      $userRepository = $em->getRepository(User::class);
      $User = $userRepository->find($id);
      $em->remove($User);
      $em->flush();

      $arr = [
        'status' => true
      ];
    } catch (Exception $e) {
      $arr = [
        'status' => false,
        'message' => 'Ocorreu um erro ao remover a categoria',
        'error' => $e->getMessage()
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
	}

	public function create(Request $req, Response $res, $args) : Response {
		$data = $req->getParsedBody();
		$em = Database::manager();

		$hasClient = ($data['hasClient'] === 'true');
		$admin = ($data['admin'] === 'true') ? 1 : 0;

		try {

			$em->getConnection()->beginTransaction();


			if ($data['id'] === '0') {
				// Verifying if already exists:
				if ($this->userExists($data['login'], $data['email'])) {
					$res->getBody()->write(json_encode(['status' => false, 'message' => 'Usuário e/ou e-mail já cadastrado(s)']));
					return $res;
				}
				// Creating user:
				$User = new User();

			} else {
				// Retrieving user from database:
				$userRepository = $em->getRepository(User::class);
				$User = $userRepository->find($data['id']);
			}
			$User->setLogin($data['login']);
			$User->setPass(md5($data['pass'])); // Se for edit devemos verificar se foi alterada antes de dar update!!! md5 do md5!!!
			$User->setName($data['name']);
			$User->setEmail($data['email']);
			$User->setAdmin($admin);
			$em->persist($User);

			if ($hasClient) {
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
			}

			$em->flush();
			$em->getConnection()->commit();

			if ($hasClient) {
				// Starting session:
				Auth::setSession($User);
			}

			$arr = [
				'status' => true,
				'user' => [
					'id' => $User->getId(),
					'login' => $User->getLogin(),
					'pass' => $User->getPass(),
					'name' => $User->getName(),
					'email' => $User->getEmail(),
					'admin' => $User->getAdmin()
				]
			];

		} catch (Exception $e) {
			$em->getConnection()->rollBack();
			$arr = [
				'status' => false,
				'message' => 'Ocorreu um erro ao criar o usuário',
				'error' => $e->getMessage()
			];
		}

		$res->getBody()->write(json_encode($arr));
		return $res;
	}

	private function userExists($p_login, $p_email) : bool {
		$em = Database::manager();
		$userRepository = $em->getRepository(User::class);

		$User = $userRepository->findOneBy(['login' => $p_login]);
		if ($User) return true;

		$User = $userRepository->findOneBy(['email' => $p_email]);
		if ($User) return true;

		return false;
	}

}