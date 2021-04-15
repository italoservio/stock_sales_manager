<?php
namespace App\Entities;


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
}