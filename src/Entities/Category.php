<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category {
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
	 * @ORM\Column(name="name", type="text", nullable=false)
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
	 */
	private $products;

	public function __construct() {
	  $this->products = new ArrayCollection();
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

	public function getProducts() {
		return $this->products;
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
}
