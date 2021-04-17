<?php
namespace App\Services;

use Slim\Views\Twig;
use Exception;

class Helper {

	public static function render($p_filename, $p_title, $p_req, $p_res) {
		$twig = Twig::fromRequest($p_req);
		return $twig->render($p_res, "{$p_filename}.twig", [
			'assetsPath' => $_ENV['BASE_PATH'] . $_ENV['ASSETS_PATH'],
			'basePath' => $_ENV['BASE_PATH'],
			'title' => $p_title
		]);
	}

}