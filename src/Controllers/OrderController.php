<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use \App\Entities\Order;

class OrderController {

  public function adminOrders(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			return Helper::render('adminOrders', $req, $res);
		} else {
			return Helper::render('login', $req, $res);
		}
	}

}