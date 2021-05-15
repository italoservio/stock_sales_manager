<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use \App\Entities\Orders;

class OrdersController {

  public function adminOrders(Request $req, Response $res, $args) : Response {
		if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
			return Helper::render('adminOrders', $req, $res);
		} else {
			return Helper::render('login', $req, $res);
		}
	}

  public function cart(Request $req, Response $res, $args) : Response {
    return Helper::render('cart', $req, $res);
  }

	// public function set(Request $req, Response $res, $args) : Response {
	// 	$data = $req->getParsedBody();
  //   $productId = (int) $data['productId'];
  //   $userId = (Auth::getSession())->getId();

  //   try {
  //     $em = Database::manager();
  //     $Orders = new Orders();
  //     $Orders->setActive('1');
  //     $Orders->setClientId('3');
  //     $em->merge($Orders);
  //     $em->flush();
  //     $arr = [
  //       'status' => true,
  //       'message' => 'Pedido finalizado com sucesso, aguardando aprovação do pagamento'
  //     ];
  //   } catch (\Exception $e) {
  //     $arr = [
  //       'status' => false,
  //       'message' => 'Ocorreu um erro ao finalizar o pedido',
  //       'error' => $e->getMessage()
  //     ];
  //   }
  //   $res->getBody()->write(json_encode($arr));
  //   return $res;
	// }

}