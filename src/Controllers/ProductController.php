<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Services\Helper;
use \App\Services\Auth;
use \App\DB\Database;
use \App\Entities\Product;

class productController
{

  public function getAll(Request $req, Response $res, $args): Response {

    $arr = [];
    $em = Database::manager();
    $productRepository = $em->getRepository(Product::class);
    $products = $productRepository->findAll();
    foreach ($products as $value) {
      $arr[] = [
        'id' => $value->getId(),
        'name' => $value->getName(),
        'desc' => $value->getDesc(),
        'qtd' => $value->getQtd(),
        'price' => $value->getPrice(),
        'category' => [
          'id' =>  $value->getCategory()->getId(),
          'name' => $value->getCategory()->getName()
        ],
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }
}
