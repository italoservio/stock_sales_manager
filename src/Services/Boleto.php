<?php

namespace App\Services;

use OpenBoleto\Banco\Caixa;
use OpenBoleto\Agente;

class Boleto
{
  public static function generate($p_client, $p_price)
  {
    $client = new Agente(
      $p_client['name'],
      $p_client['cpf'],
      $p_client['address'],
      $p_client['zip'],
      $p_client['city'],
      $p_client['uf']
    );
    $store = new Agente(
      $_ENV['RECEIVER_NAME'],
      $_ENV['RECEIVER_DOC'],
      $_ENV['RECEIVER_ADDRESS'],
      $_ENV['RECEIVER_ZIP_CODE'],
      $_ENV['RECEIVER_CITY'],
      $_ENV['RECEIVER_STATE']
    );

    $ticket = new Caixa([
      'dataVencimento' => new \DateTime('2013-01-24'),
      'valor' => floatval($p_price),
      'sequencial' => 1234567,
      'sacado' => $client,
      'cedente' => $store,
      'agencia' => '3311',
      'carteira' => 'SR',
      'conta' => '24599',
      'contaDv' => 3,
      'logoPath' => $_ENV['BASE_PATH'] . $_ENV['ASSETS_PATH'] . '/img/sys/BoletoHeader.svg',
      'descricaoDemonstrativo' => [
        'Compra de produtos on-line em: STOCK SALES MANAGER',
        'Produtos ocultados por segurança do sacado',
        'Os produtos referentes ao pedido serão enviados após a aprovação do pagamento desta guia'
      ],
      'instrucoes' => ['Não receber após o vencimento.'],
    ]);

    return $ticket->getOutput();
  }
}
