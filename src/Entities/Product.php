<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product {
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
	 * @ORM\Column(name="name", type="text", length=128, nullable=false)
	 */
	private $name;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="desc", type="text", length=1024, nullable=true)
	 */
	private $desc;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="qtd", type="integer", nullable=false)
	 */
	private $qtd = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=false)
	 */
	private $price = '0';

	/**
	 * @var string
	 *
	 * @ORM\Column(name="createdAt", type="text", nullable=false, options={"default"="datetime('now')"})
	 */
	private $createdat;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="updatedAt", type="text", nullable=false, options={"default"="datetime('now')"})
	 */
	private $updatedat;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="imagePath", type="text", nullable=true)
	 */
	private $imagepath;

	/**
	 * @var int|null
	 *
	 * @ORM\Column(name="createdBy", type="integer", nullable=true)
	 */
	private $createdby;

	/**
	 * @var int|null
	 *
	 * @ORM\Column(name="categoryId", type="integer", nullable=true)
	 */
	private $categoryid;

	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="product")
	 * @ORM\JoinColumn(name="categoryId", referencedColumnName="id")
	 */
	private $category;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="product")
	 * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
	 */
	private $user;

	/**
	 * @ORM\OneToMany(targetEntity="Orderproduct", mappedBy="product")
	 */
	private $orders;

	public function __construct() {
	  $this->orders = new ArrayCollection();

	  date_default_timezone_set('America/Sao_Paulo');
    $this->createdat = (string) date('Y-m-d H:i:s');
    $this->updatedat = (string) date('Y-m-d H:i:s');
	}

	/*
   * Getters
   */
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getDesc() {
    return $this->desc;
  }

  public function getQtd() {
    return $this->qtd;
  }

  public function getPrice() {
    return $this->price;
  }

  public function getCreatedAt() {
    return $this->createdat;
  }

  public function getUpdatedAt() {
    return $this->updatedat;
  }

  public function getImagePath() {
    return $this->imagepath;
  }

  public function getCreatedBy() {
    return $this->createdby;
  }

  public function getCategoryId() {
    return $this->categoryid;
  }

  public function getCategory() {
    return $this->category;
  }

  public function getUser() {
    return $this->user;
  }

  public function getOrders() {
    return $this->orders;
  }

  /*
   * Setters
   */
  public function setId($p_id) {
    $this->id = $p_id;
  }

  public function setName($p_name) {
    $this->name = $p_name;
  }

  public function setDesc($p_desc) {
    $this->desc = $p_desc;
  }

  public function setQtd($p_qtd) {
    $this->qtd = $p_qtd;
  }

  public function setPrice($p_price) {
    $this->price = $p_price;
  }

  public function setUpdatedAt($p_updatedAt) {
    $this->updatedat = $p_updatedAt;
  }

  public function setImagePath($p_imagePath) {
    $this->imagepath = $p_imagePath;
  }

  public function setCreatedBy($p_createdBy) {
    $this->createdby = $p_createdBy;
  }

  public function setCategoryId($p_categoryId) {
    $this->categoryid = $p_categoryId;
  }

}