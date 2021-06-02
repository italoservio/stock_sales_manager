<?php

namespace App\Entities;

use \App\Entities\Client;
use \App\Entities\Orderproduct;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Orders
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
   * @ORM\Column(name="active", type="integer", nullable=false)
   */
  private $active = '0';

  /**
   * @var int
   *
   * @ORM\Column(name="payed", type="integer", nullable=false)
   */
  private $payed = '0';

  /**
   * @var string
   *
   * @ORM\Column(name="createdAt", type="text", nullable=false, options={"default"="datetime('now')"})
   */
  private $createdat;

  /**
   * @var int
   *
   * @ORM\Column(name="clientId", type="integer", nullable=false)
   */
  private $clientid;

  /**
   * @ORM\OneToMany(targetEntity="Orderproduct", mappedBy="orders")
   */
  private $orderproducts;

  /**
   * @ORM\ManyToOne(targetEntity="Client", inversedBy="orders")
   * @ORM\JoinColumn(name="clientId", referencedColumnName="id")
   */
  private $client;

  public function __construct()
  {
    $this->orderproducts = new ArrayCollection();

    date_default_timezone_set('America/Sao_Paulo');
    $this->createdat = (string) date('Y-m-d H:i:s');
  }

  /*
   * Getters
   */
  public function getId()
  {
    return $this->id;
  }

  public function getActive()
  {
    return $this->active;
  }

  public function getCreatedAt()
  {
    return $this->createdat;
  }

  public function getClientId()
  {
    return $this->clientid;
  }

  public function getOrderProducts()
  {
    return $this->orderproducts;
  }

  public function getClient()
  {
    return $this->client;
  }

  public function getPayed()
  {
    return $this->payed;
  }

  /*
   * Setters
   */
  public function setId($p_id)
  {
    $this->id = $p_id;
  }

  public function setActive($p_active)
  {
    $this->active = $p_active;
  }

  public function setCreatedAt($p_createdat)
  {
    $this->createdat = $p_createdat;
  }

  public function setClientId($p_clientid)
  {
    $this->clientid = $p_clientid;
  }

  public function setCliente($p_Cliente)
  {
    $this->client = $p_Cliente;
  }

  public function setPayed($p_Payed)
  {
    $this->payed = $p_Payed;
  }
}
