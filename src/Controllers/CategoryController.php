<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use \App\Entities\Category;

class CategoryController {

  public function adminCategories(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			return Helper::render('adminCategories', $req, $res);
		} else {
			return Helper::render('login', $req, $res);
		}
	}

}