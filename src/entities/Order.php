<?php

namespace App\Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="order")
 * @ORM\Entity
 */
class Order
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
   * @var string
   *
   * @ORM\Column(name="createdAt", type="text", nullable=false, options={"default"="datetime('now')"})
   */
  private $createdat = 'datetime(\'now\')';

  /**
   * @var int
   *
   * @ORM\Column(name="clientId", type="integer", nullable=false)
   */
  private $clientid;

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

  public function getCreatedat()
  {
    return $this->createdat;
  }

  public function getClientid()
  {
    return $this->clientid;
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

  public function setCreatedat($p_createdat)
  {
    $this->createdat = $p_createdat;
  }

  public function setClientid($p_clientid)
  {
    $this->clientid = $p_clientid;
  }
}
