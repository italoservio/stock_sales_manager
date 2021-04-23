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
			default: return 'SSM';
		}
	}

	public static function render($p_filename, $p_req, $p_res) {
		$User = null;
		if (Auth::hasSession()) {
			$User = Auth::getSession();
			$User = [
				'id' 		=> $User->getId(),
				'login' => $User->getLogin(),
				'pass' 	=> $User->getPass(),
				'name' 	=> $User->getName(),
				'email' => $User->getEmail(),
				'admin' => $User->getAdmin()
			];
		}

		$twig = Twig::fromRequest($p_req);

		return $twig->render($p_res, "{$p_filename}.twig", [
			'assetsPath' => $_ENV['BASE_PATH'] . $_ENV['ASSETS_PATH'],
			'basePath' => $_ENV['BASE_PATH'],
			'title' => self::pageTitle($p_filename),
			'user' => $User
		]);
	}

}