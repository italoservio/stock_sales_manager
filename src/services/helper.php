<?php
namespace App\Services;

use Exception;

class Helper {

	public static function render($p_pageName) {
		$publicPath = __DIR__ . '/../../public/pages/';
		$filename = $publicPath . $p_pageName . '.html';
		try {
			if (file_exists($filename)) {
				return file_get_contents($filename);
			} else {
				throw new Exception('Error Processing Request, File doesn\'t exists.', 1);
			}
		} catch (Exception $e) {
			echo json_encode([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}

}