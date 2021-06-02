<?php

namespace App\Entities;

use \App\Entities\Product;
use \App\Entities\Orders;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orderproduct
 *
 * @ORM\Table(name="orderProduct")
 * @ORM\Entity
 */
class Orderproduct
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var int
   *
   * @ORM\Column(name="productId", type="integer", nullable=false)
   */
  private $productid;

  /**
   * @var int
   *
   * @ORM\Column(name="orderId", type="integer", nullable=false)
   */
  private $orderid;

  /**
   * @var int
   *
   * @ORM\Column(name="qtd", type="integer", nullable=false, options={"default"="1"})
   */
  private $qtd = 1;

  /**
   * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderProduct")
   * @ORM\JoinColumn(name="productId", referencedColumnName="id")
   */
  private $product;

  /**
   * @ORM\ManyToOne(targetEntity="Orders", inversedBy="orderProduct")
   * @ORM\JoinColumn(name="orderId", referencedColumnName="id")
   */
  private $order;

  /*
   * Getters
   */
  public function getId()
  {
    return $this->id;
  }

  public function getProductId()
  {
    return $this->productid;
  }

  public function getOrderId()
  {
    return $this->orderid;
  }

  public function getQtd()
  {
    return $this->qtd;
  }

  public function getProduct()
  {
    return $this->product;
  }

  public function getOrder()
  {
    return $this->order;
  }

  /*
   * Setters
   */
  public function setId($p_id)
  {
    $this->id = $p_id;
  }

  public function setProductId($p_productid)
  {
    $this->productid = $p_productid;
  }

  public function setOrderId($p_orderid)
  {
    $this->orderid = $p_orderid;
  }

  public function setQtd($p_qtd)
  {
    $this->qtd = $p_qtd;
  }

  public function setOrder($p_order)
  {
    $this->order = $p_order;
  }
  public function setProduct($p_product)
  {
    $this->product = $p_product;
  }
}
