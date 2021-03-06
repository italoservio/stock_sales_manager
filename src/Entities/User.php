<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
   * @var string
   *
   * @ORM\Column(name="imagePath", type="text", length=1024, nullable=true)
   */
  private $imagepath;

  /**
   * @var string
   *
   * @ORM\Column(name="login", type="text", length=64, nullable=true)
   */
  private $login;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="text", length=128, nullable=false)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="pass", type="text", length=128, nullable=false)
   */
  private $pass;

  /**
   * @var string
   *
   * @ORM\Column(name="email", type="text", length=128, nullable=false)
   */
  private $email;

  /**
   * @var int
   *
   * @ORM\Column(name="admin", type="integer", nullable=false)
   */
  private $admin = '0';

  /**
   * @var string
   *
   * @ORM\Column(name="createdAt", type="text", nullable=false, options={"default"="datetime('now')"})
   */
  private $createdat;

  /**
   * @var string|null
   *
   * @ORM\Column(name="updatedAt", type="text", nullable=true, options={"default"="datetime('now')"})
   */
  private $updatedat;

  /**
   * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
   */
  private $products;

  public function __construct()
  {
    $this->products = new ArrayCollection();

    date_default_timezone_set('America/Sao_Paulo');
    $this->createdat = (string) date('Y-m-d H:i:s');
    $this->updatedat = (string) date('Y-m-d H:i:s');
  }

  /*
   * Getters
   */
  public function getId()
  {
    return $this->id;
  }

  public function getImagePath()
  {
    return $this->imagepath;
  }

  public function getLogin()
  {
    return $this->login;
  }

  public function getPass()
  {
    return $this->pass;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getAdmin()
  {
    return $this->admin;
  }

  public function getCreatedAt()
  {
    return $this->createdat;
  }

  public function getUpdatedAt()
  {
    return $this->updatedat;
  }

  public function getProducts()
  {
    return $this->products;
  }

  /*
   * Setters
   */
  public function setId($p_id)
  {
    $this->id = $p_id;
  }

  public function setImagePath($p_imagePath)
  {
    $this->imagepath = $p_imagePath;
  }

  public function setLogin($p_login)
  {
    $this->login = $p_login;
  }

  public function setPass($p_pass)
  {
    $this->pass = $p_pass;
  }

  public function setName($p_name)
  {
    $this->name = $p_name;
  }

  public function setEmail($p_email)
  {
    $this->email = $p_email;
  }

  public function setAdmin($p_admin)
  {
    $this->admin = $p_admin;
  }

  public function setCreatedAt($p_createdAt)
  {
    $this->createdat = $p_createdAt;
  }

  public function setUpdatedAt($p_updatedAt)
  {
    $this->updatedat = $p_updatedAt;
  }
}
