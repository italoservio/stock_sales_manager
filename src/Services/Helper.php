<?php
namespace App\Services;

use \App\Services\Auth;
use Slim\Views\Twig;
use Exception;

class Helper {

	public static function pageTitle($p_filename) {
		switch ($p_filename) {
			case 'index': return 'SSM: Página Inicial';
			case 'login': return 'SSM: Acesso';
      case 'signup': return 'SSM: Cadastro';
			case 'admin': return 'SSM Admin: Home';
			case 'adminOrders': return 'SSM Admin: Vendas';
			case 'adminUsers': return 'SSM Admin: Usuários';
			case 'adminProducts': return 'SSM Admin: Produtos';
			case 'adminCategories': return 'SSM Admin: Categorias';
			case 'adminStatistics': return 'SSM Admin: Estatísticas';
			case 'profile': return 'SSM: Perfil';
      case 'product': return 'SSM: Produto';
      case 'cart': return 'SSM: Sacola';
      case 'order': return 'SSM: Pedido';
			default: return 'SSM';
		}
	}

	public static function render($p_filename, $p_req, $p_res, $p_optionals = []) {
		$User = null;
		$Client = null;
		if (Auth::hasSession()) {
			$arrSession = Auth::getCompleteSession();
			$User = $arrSession['user'];
			$Client = $arrSession['client'];
			$User = [
				'id' 		=> $User->getId(),
				'login' => $User->getLogin(),
				'pass' 	=> $User->getPass(),
				'name' 	=> $User->getName(),
				'email' => $User->getEmail(),
				'admin' => $User->getAdmin(),
				'imagePath' => $User->getImagePath()
			];
			if (!is_null($Client)) {
				$Client = [
					'id' 		 => $Client->getId(),
					'cep' 	 => $Client->getCep(),
					'cidade' => $Client->getCidade(),
					'estado' => $Client->getEstado(),
					'bairro' => $Client->getBairro(),
					'numero' => $Client->getNumero(),
					'logradouro' 	=> $Client->getLogradouro(),
					'complemento' => $Client->getComplemento()
				];
			}
		}

		$twig = Twig::fromRequest($p_req);

		return $twig->render($p_res, "{$p_filename}.twig", [
			'assetsPath' => $_ENV['BASE_PATH'] . $_ENV['ASSETS_PATH'],
			'basePath' => $_ENV['BASE_PATH'],
			'title' => self::pageTitle($p_filename),
			'user' => $User,
			'client' => $Client,
			'optionals' => $p_optionals
		]);
	}

}