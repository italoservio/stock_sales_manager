<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\Services\Boleto;

use App\Entities\Client;
use App\Entities\Orderproduct;
use App\Entities\Product;
use App\Entities\User;
use \App\Database\Database;
use \App\Entities\Orders;

class OrdersController {

  public function getBoleto(Request $req, Response $res, $args): Response {
    try {
      $price = ($req->getQueryParams())['price'];
      $em = Database::manager();
      $clientRepository = $em->getRepository(Client::class);
      $Client = $clientRepository->findOneBy(['userid' => (Auth::getSession())->getId()]);
      $arr = [
        'name' => $Client->getUser()->getName(),
        'cpf' => '00000000000',
        'address' => $Client->getLogradouro() . ', ' . $Client->getNumero() . ', ' . $Client->getComplemento(),
        'zip' => $Client->getCep(),
        'city' => $Client->getCidade(),
        'uf' => $Client->getEstado()
      ];
      $ticket = Boleto::generate($arr, $price);
      $res->getBody()->write($ticket);
    } catch (\Exception $e) {
      $res->getBody()->write([
        'status' => false,
        'message' => 'Ocorreu um erro ao gerar o boleto de pagamento'
      ]);
    }
    return $res;
  }

  public function adminOrders(Request $req, Response $res, $args): Response {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      return Helper::render('adminOrders', $req, $res);
    } else {
      return Helper::render('login', $req, $res);
    }
  }

  public function getByUserId(Request $req, Response $res, $args): Response {
    $arr = [];
    if (Auth::hasSession()) {
      try {
        $userId = (Auth::getSession())->getId();
        $em = Database::manager();
        $clientRepository = $em->getRepository(Client::class);
        $orderRepository = $em->getRepository(Orders::class);
        $orderProductRepository = $em->getRepository(Orderproduct::class);
        $productRepository = $em->getRepository(Product::class);

        $Client = $clientRepository->findOneBy(['userid' => $userId]);
        $Orders = $orderRepository->findBy([
            'clientid' => $Client->getId(),
            'active' => 1
        ]);

        foreach ($Orders as $Order) {
          $products = [];
          $OrderProducts = $orderProductRepository->findBy(['orderid' => $Order->getId()]);
          foreach ($OrderProducts as $OrderProduct) {
            $Product = $productRepository->findOneBy(['id' => $OrderProduct->getProductId()]);
            $products[] = [
                'id' => $Product->getId(),
                'qtd' => $OrderProduct->getQtd(),
                'name' => $Product->getName(),
                'price' => $Product->getPrice(),
                'imagePath' => $Product->getImagePath()
            ];
          }
          $arr[] = [
              'id' => $Order->getId(),
              'createdAt' => date('d/m/Y H:i:s', strtotime($Order->getCreatedAt())),
              'products' => $products
          ];
        }
        $arr = [
            'status' => true,
            'orders' => $arr
        ];
      } catch (\Exception $e) {
        $arr[] = [
            'status' => false,
            'message' => 'Ocorreu um erro ao buscar os pedidos do usuário',
            'error' => $e->getMessage()
        ];
      }
      $res->getBody()->write(json_encode($arr));
      return $res;
    }
  }

  public function cart(Request $req, Response $res, $args): Response {
    return Helper::render('cart', $req, $res);
  }

  public function get(Request $req, Response $res, $args): Response {
    return Helper::render('order', $req, $res, ['orderId' => $args['id']]);
  }

  public function getById(Request $req, Response $res, $args): Response {
    if (Auth::hasSession()) {
      try {
        $em = Database::manager();
        $User = Auth::getSession();

        $orderRepository = $em->getRepository(Orders::class);
        $clientRepository = $em->getRepository(Client::class);
        $userRepository = $em->getRepository(User::class);

        $Order = $orderRepository->findOneBy(['id' => $args['id']]);
        $Client = $clientRepository->findOneBy(['id' => $Order->getClientId()]);
        $UserOrder = $userRepository->findOneBy(['id' => $Client->getUserId()]);

        // Only the owner can see your order:
        if ($UserOrder->getId() === $User->getId()) {
          $arr = [];
          $orderProductRepository = $em->getRepository(Orderproduct::class);
          $productRepository = $em->getRepository(Product::class);

          $orderProducts = $orderProductRepository->findBy(['orderid' => (int)$args['id']]);

          foreach ($orderProducts as $value) {
            $Product = $productRepository->findOneBy(['id' => $value->getProductId()]);
            $arr[] = [
                'id' => $Product->getId(),
                'name' => $Product->getName(),
                'price' => $Product->getPrice(),
                'imagePath' => $Product->getImagePath(),
                'qtd' => $value->getQtd(),
            ];
          }

          $arr = [
              'status' => true,
              'products' => $arr
          ];
        } else {
          $arr = [
              'status' => false,
              'redirect' => true
          ];
        }
      } catch (\Exception $e) {
        $arr = [
            'status' => false,
            'message' => 'Ocorreu um erro ao buscar os produtos deste pedido',
            'error' => $e->getMessage()
        ];
      }
    } else {
      $arr = [
          'status' => false,
          'redirect' => true
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }

  public function set(Request $req, Response $res, $args): Response {
    date_default_timezone_set('America/Sao_Paulo');
    $arr = [];
    $arrList = [];

    if (Auth::hasSession()) {
      $User = Auth::getSession();
      $data = $req->getParsedBody();
      $qtd = 0;
      try {
        $em = Database::manager();
        $clientRepository = $em->getRepository(Client::class);
        $productRepository = $em->getRepository(Product::class);

        for ($i = 0; $i < count($data["listProduct"]); $i++) {
          $Product = $productRepository->findOneBy(['id' => $data["listProduct"][$i]]);
          $qtdTotal = $Product->getQtd() - $data["listQtd"][$i];
          if ($qtdTotal < 0) {
            $qtd = 1;
            $arrList = [
                'id' => $Product->getId(),
                'name' => $Product->getName(),
                'price' => $Product->getPrice(),
                'qtd' => $Product->getQtd(),
            ];
          }
        }
        if ($qtd === 0) {
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
            $OrderProduct->setOrderId($Orders->getId());
            $OrderProduct->setProductId($Product->getId());
            $OrderProduct->setQtd($data["listQtd"][$i]);
            $Product->setQtd($Product->getQtd() - $data["listQtd"][$i]);
            $em->persist($OrderProduct);
            $em->persist($Product);
            $em->flush();
          }

          $arr = [
              'status' => true,
              'orderId' => $Orders->getId(),
              'message' => 'Pedido finalizado com sucesso'
          ];
        } else {
          $arr = [
              'status' => false,
              'products' => $arrList,
              'message' => 'Algum produto no carrinho está com o estoque abaixo do total pedido, favor verificar'
          ];
        }

      } catch (\Exception $e) {
        $arr = [
            'status' => false,
            'message' => 'Ocorreu um erro ao finalizar o pedido',
            'error' => $e->getMessage()
        ];
      }
    } else {
      $arr = [
          'status' => false,
          'redirect' => true
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }
}