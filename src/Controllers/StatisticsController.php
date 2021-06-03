<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

use \App\Services\Auth;
use \App\Database\Database;

class StatisticsController
{

  public function getLineChartData(Request $req, Response $res, $args): Response
  {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      try {
        $em = Database::manager();
        $arr = [];
        for ($i = 1; $i <= 12; $i++) {
          $mounth = ($i < 10) ? "0$i" : $i;

          $sql = "SELECT count(o.id) AS qtd
          FROM orders o
          WHERE o.payed = 1
          AND o.createdAt BETWEEN '2021-$mounth-01 00:00:00' AND '2021-$mounth-30 23:59:59'";

          $row =  $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
          $arr[] = (int) $row[0]['qtd'];
        }
        $arr = [
          'status' => true,
          'data' => $arr
        ];
      } catch (Exception $e) {
        $arr = [
          'status' => false,
          'message' => 'Ocorreu um erro ao buscar os dados do gráfico',
          'error' => $e->getMessage()
        ];
      }
      $res->getBody()->write(json_encode($arr));
      return $res;
    }
  }

  public function getPieChartData(Request $req, Response $res, $args): Response
  {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      try {
        $em = Database::manager();
        $arr = [];
        $sql1 = "SELECT count(o.id) AS qtd
        FROM orders o
        WHERE o.payed = 1";

        $sql2 = "SELECT count(o.id) AS qtd
        FROM orders o
        WHERE o.payed = 0";

        $row1 =  $em->getConnection()->executeQuery($sql1)->fetchAllAssociative();
        $row2 =  $em->getConnection()->executeQuery($sql2)->fetchAllAssociative();

        $arr[] = (int) $row1[0]['qtd'];
        $arr[] = (int) $row2[0]['qtd'];

        $arr = [
          'status' => true,
          'data' => $arr
        ];
      } catch (Exception $e) {
        $arr = [
          'status' => false,
          'message' => 'Ocorreu um erro ao buscar os dados do gráfico',
          'error' => $e->getMessage()
        ];
      }
      $res->getBody()->write(json_encode($arr));
      return $res;
    }
  }

  public function getBarChartData(Request $req, Response $res, $args): Response
  {
    if (Auth::hasSession() && (Auth::getSession())->getAdmin() === 1) {
      try {
        $em = Database::manager();
        $arr = [];
        for ($i = 1; $i <= 12; $i++) {
          $mounth = ($i < 10) ? "0$i" : $i;

          $sql = "SELECT sum(p.price) AS price
          FROM orders o
          LEFT JOIN orderProduct op ON o.id = op.orderId
          LEFT JOIN product p ON p.id = op.productId
          WHERE o.createdAt BETWEEN '2021-$mounth-01 00:00:00' AND '2021-$mounth-30 23:59:59'
          AND o.payed = 1";

          $row =  $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
          $arr[] = floatval($row[0]['price']);
        }
        $arr = [
          'status' => true,
          'data' => $arr
        ];
      } catch (Exception $e) {
        $arr = [
          'status' => false,
          'message' => 'Ocorreu um erro ao buscar os dados do gráfico',
          'error' => $e->getMessage()
        ];
      }
      $res->getBody()->write(json_encode($arr));
      return $res;
    }
  }

}
