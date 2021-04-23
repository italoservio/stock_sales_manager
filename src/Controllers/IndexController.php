<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;


class IndexController {

	public function index(Request $req, Response $res, $args) {
		return Helper::render('index', $req, $res);
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

	public function adminStatistics(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			return Helper::render('adminStatistics', $req, $res);
		} else {
			return Helper::render('login', $req, $res);
		}
	}

}