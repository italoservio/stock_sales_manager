<?php

namespace App\Controllers;

use App\Entities\Client;
use App\Entities\Orderproduct;
use App\Entities\Product;
use App\Entities\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use \App\Entities\Orders;

class OrdersController {

  public function adminOrders(Request $req, Response $res, $args): Response {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      return Helper::render('adminOrders', $req, $res);
    } else {
      return Helper::render('login', $req, $res);
    }
  }

  public function cart(Request $req, Response $res, $args): Response {
    return Helper::render('cart', $req, $res);
  }

  public function set(Request $req, Response $res, $args): Response {
    date_default_timezone_set('America/Sao_Paulo');
    $arr = [];
    if (Auth::hasSession()) {
      $User = Auth::getSession();
      $data = $req->getParsedBody();
      $productId = $data['listProduct'];
      try {
        $em = Database::manager();
        $clientRepository = $em->getRepository(Client::class);
        $productRepository = $em->getRepository(Product::class);
        $Client = $clientRepository->findOneBy(['userid' => $User->getId()]);

        $Orders = new Orders();
        $Orders->setActive(1);
        $Orders->setCliente($Client);
        $Orders->setClientId($Client->getId());
        $Orders->setCreatedAt(date('Y-m-d H:i:s'));
        $em->persist($Orders);
        $em->flush();

        for ($i = 0; $i < count($data["listProduct"]); $i++) {
          $Product = $productRepository->findOneBy(['id' => $data["listProduct"][$i]]);
          $OrderProduct = new Orderproduct();
          $OrderProduct->setOrder($Orders);
          $OrderProduct->setProduct($Product);
          $OrderProduct->setOrderid($Orders->getId());
          $OrderProduct->setProductid($Product->getId());
          $OrderProduct->setQtd($data["listQtd"][$i]);
          $Product->setQtd($Product->getQtd() - $data["listQtd"][$i]);
          $em->persist($OrderProduct);
          $em->persist($Product);
          $em->flush();
        }


        $arr = ['status' => true, 'message' => 'Pedido finalizado com sucesso, aguardando aprovação do pagamento'];
      } catch (Exception $e) {
        $arr = ['status' => false, 'message' => 'Ocorreu um erro ao finalizar o pedido', 'error' => $e->getMessage()];
      }
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }
}