<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

use \App\Services\Helper;
use \App\Services\Auth;
use \App\Database\Database;

use \App\Entities\Category;
use \App\Entities\Product;

class ProductController
{

  public function details(Request $req, Response $res, $args): Response
  {
    $em = Database::manager();
    $productRepository = $em->getRepository(Product::class);
    $Product = $productRepository->findOneBy(['id' => $args['id']]);
    if (!is_null($Product)) {
      $product = [
        'id' => $args['id'],
        'name' => $Product->getName(),
        'desc' => $Product->getDesc(),
        'qtd' => $Product->getQtd(),
        'price' => $Product->getPrice(),
        'imagePath' => $Product->getImagePath(),
        'category' => [
          'id' => $Product->getCategory()->getId()
        ]
      ];
    }
    return Helper::render('product', $req, $res, ['product' => $product]);
  }

  public function adminProducts(Request $req, Response $res, $args): Response
  {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      return Helper::render('adminProducts', $req, $res);
    } else {
      return Helper::render('login', $req, $res);
    }
  }

  public function getAll(Request $req, Response $res, $args): Response
  {
    $arr = [];
    $category = ($req->getQueryParams())['category'];
    try {
      $em = Database::manager();
      $productRepository = $em->getRepository(Product::class);

      if (!is_array($category)) {
        $products = ($category == "0") ? $productRepository->findAll() : $productRepository->findBy(array('categoryid' => (int) $category));

        if (!is_null($products)) {
          foreach ($products as $p) {
            $arr[] = [
              'id' => $p->getId(),
              'name' => $p->getName(),
              'desc' => $p->getDesc(),
              'qtd' => $p->getQtd(),
              'price' => $p->getPrice(),
              'imagePath' => $p->getImagePath(),
              'category' => [
                'id' => $p->getCategory()->getId(),
                'name' => $p->getCategory()->getName()
              ]
            ];
          }
        }
      } else {
        foreach ($category as $key => $categoryId) {
          $products = $productRepository->findBy(array('categoryid' => $category[$key]));
          if (!is_null($products)) {
            foreach ($products as $value) {
              $arr[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'desc' => $value->getDesc(),
                'qtd' => $value->getQtd(),
                'price' => $value->getPrice(),
                'imagePath' => $value->getImagePath(),
                'category' => [
                  'id' => $value->getCategory()->getId(),
                  'name' => $value->getCategory()->getName()
                ]
              ];
            }
          }
        }
      }
      $arr = (empty($arr)) ? ['status' => false, 'message' => 'Não há produtos para exibir']
        : ['status' => true, 'products' => $arr];
    } catch (Exception $e) {
      $arr = [
        'status' => false,
        'message' => 'Ocorreu um erro ao tentar buscar os produtos',
        'error' => $e->getMessage()
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }

  public function get(Request $req, Response $res, $args): Response
  {
    $arr = [];
    $id = $args['id'];
    try {
      $em = Database::manager();
      $productRepository = $em->getRepository(Product::class);
      $Product = $productRepository->findOneBy(['id' => $id]);
      if (!is_null($Product)) {
        $product = [
          'id' => $Product->getId(),
          'name' => $Product->getName(),
          'desc' => $Product->getDesc(),
          'qtd' => $Product->getQtd(),
          'price' => $Product->getPrice(),
          'imagePath' => $Product->getImagePath(),
          'category' => [
            'id' => $Product->getCategory()->getId(),
          ]
        ];
        $arr = [
          'status' => true,
          'product' => $product
        ];
      } else {
        $arr = [
          'status' => false,
          'message' => 'Não foi possível encontrar o produto'
        ];
      }
    } catch (Exception $e) {
      $arr = [
        'status' => false,
        'message' => 'Ocorreu um erro ao buscar o produto',
        'error' => $e->getMessage()
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }

  public function create(Request $req, Response $res, $args): Response
  {
    date_default_timezone_set('America/Sao_Paulo');
    $arr = [];
    $route = '../public/assets/img/sys/products/';
    $data = $req->getParsedBody();


    $em = Database::manager();
    $em->getConnection()->beginTransaction();

    try {

      if ($data["imageUpdate"] !== '1') {
        $file = $_FILES['file'];
        $file['name'] = md5(date('YmdHis')) . '_' . $file['name'];
        // Movendo arquivo:
        move_uploaded_file($file['tmp_name'], $route . $file['name']);
      }
      $Product = new Product();

      // Criando produto:
      $User = Auth::getSession();
      $categoryRepository = $em->getRepository(Category::class);
      $Category = $categoryRepository->find((int)$data['category']);
      $userId = $User->getId();

      if ($data['id'] === '0') {
        $Product->setName($data['name']);
        $Product->setQtd($data['qtd']);
        $Product->setPrice($data['price']);
        $Product->setDesc($data['desc']);
        $Product->setImagePath("products/" . $file['name']);
        $Product->setCreatedBy($User->getId());
        $Product->setCategoryId($data['category']);
        $Product->setUser($User);
        $Product->setCategory($Category);
        $Product->setUpdatedAt(date('Y-m-d H:i:s'));
        $em->merge($Product);
        $em->flush();
        $em->getConnection()->commit();

        $arr = [
          'status' => true,
          'message' => 'Produto criado com sucesso'
        ];
      } else {
        $productRepository = $em->getRepository(Product::class);
        $Product = $productRepository->find($data['id']);

        $Product->setName($data['name']);
        $Product->setQtd($data['qtd']);
        $Product->setPrice($data['price']);
        $Product->setDesc($data['desc']);
        if ($data["imageUpdate"] !== '1') {
          $nameImage = explode("/", $Product->getImagePath());
          unlink($route . $nameImage[1]);

          $Product->setImagePath("products/" . $file['name']);
        }
        $Product->setCreatedBy($userId);
        $Product->setCategoryId($data['category']);
        $Product->setUpdatedAt(date('Y-m-d H:i:s'));
        $em->persist($Product);
        $em->flush();
        $em->getConnection()->commit();

        $arr = [
          'status' => true,
          'message' => 'Produto editado com sucesso'
        ];
      }
    } catch (Exception $e) {
      $em->getConnection()->rollBack();
      $arr = [
        'status' => false,
        'message' => 'Falha na criação do produto',
        'error' => $e->getMessage()
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }

  public function delete(Request $req, Response $res, $args): Response
  {
    $id = $args['id'];
    $arr = [];
    try {
      $em = Database::manager();
      $productRepository = $em->getRepository(Product::class);
      $Product = $productRepository->find($id);

      $nameImage = explode("/", $Product->getImagePath());
      unlink('../public/assets/img/sys/products/' . $nameImage[1]);
      $em->remove($Product);
      $em->flush();

      $arr = [
        'status' => true
      ];
    } catch (Exception $e) {
      $arr = [
        'status' => false,
        'message' => 'Ocorreu um erro ao remover o produto',
        'error' => $e->getMessage()
      ];
    }
    $res->getBody()->write(json_encode($arr));
    return $res;
  }
}
