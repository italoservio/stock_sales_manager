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

	public function changeImage(Request $req, Response $res, $args) : Response {
		date_default_timezone_set('America/Sao_Paulo');
		$em = Database::manager();

		try {
			$file = $_FILES['imgFile'];

			// Only less than 1MB:
			if ($file['size'] > 1048576) {
				$arr = ['status' => false, 'message' => 'Não são aceitas imagens maiores do que 1Mb'];
				$res->getBody()->write(json_encode($arr));
				return $res;
			}

			$file['name'] = md5(date('YmdHis')) . '_' . $file['name'];

			if (move_uploaded_file($file['tmp_name'], '../public/assets/img/profiles/' . $file['name'])) {
				$UserAuth = Auth::getSession();
				$userRepository = $em->getRepository(User::class);
				$User = $userRepository->find($UserAuth->getId());

				// Removing old image:
				$oldImagePath = $User->getImagePath();
				$fullOldImagePath = '../public/assets' . $User->getImagePath();
				if ($oldImagePath !== '/img/profiles/default.jpg') {
					if (file_exists($fullOldImagePath)) {
						unlink($fullOldImagePath);
					}
				}

				$User->setImagePath('/img/profiles/' . $file['name']);
				$em->persist($User);
				$em->flush();
				$arr = [
					'status' => true,
					'message' => 'Imagem alterada com sucesso, suas mudanças serão aplicadas na proxima vez em que logar'
				];
			} else {
				$arr = [
					'status' => false,
					'message' => 'Ocorreu um erro ao mover o arquivo enviado'
				];
			}
		} catch (Exception $e) {
			$arr = [
				'status' => false,
				'message' => 'Ocorreu um erro ao alterar a imagem',
				'error' => $e->getMessage()
			];
		}
		$res->getBody()->write(json_encode($arr));
		return $res;
	}

	public function authenticate(Request $req, Response $res, $args) : Response {
		$em = Database::manager();
		$arr = $req->getQueryParams();

		$userRepository = $em->getRepository(User::class);
		$User = $userRepository->findOneBy([
			'login' => $arr['user'],
			'pass' => $arr['pass']
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
		date_default_timezone_set('America/Sao_Paulo');
		$data = $req->getParsedBody();
		$em = Database::manager();

		// Preparing data:
		$hasClient = ($data['hasClient'] === 'true');
		$admin = ($data['admin'] === 'true') ? 1 : 0;

		try {
			$em->getConnection()->beginTransaction();

			if ($data['id'] === '0') {
				if ($this->userExists($data['login'], $data['email'])) {
					$res->getBody()->write(json_encode(['status' => false, 'message' => 'Usuário e/ou e-mail já cadastrado(s)'])); return $res;
				}
				$User = new User();
			} else {
				$userRepository = $em->getRepository(User::class);
				$User = $userRepository->find($data['id']);
				$User->setUpdatedAt(date('Y-m-d H:i:s'));
			}
			$User->setLogin($data['login']);
			$User->setPass($data['pass']);
			$User->setName($data['name']);
			$User->setEmail($data['email']);
			$User->setAdmin($admin);
			$em->persist($User);

			if ($hasClient) {
				if ($data['id'] === '0') {
					$Client = new Client();
				} else {
					$clientRepository = $em->getRepository(Client::class);
					$Client = $clientRepository->findOneBy(['id' => $data['clientId']]);
				}
				$Client->setCep($data['cep']);
				$Client->setCidade($data['cidade']);
				$Client->setEstado($data['estado']);
				$Client->setBairro($data['bairro']);
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
				if (Auth::hasSession()) Auth::clearSession();
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