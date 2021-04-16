<?php

namespace App\Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client
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
	 * @var string|null
	 *
	 * @ORM\Column(name="cep", type="text", nullable=true)
	 */
	private $cep;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="cidade", type="text", nullable=true)
	 */
	private $cidade;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="bairro", type="text", nullable=true)
	 */
	private $bairro;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="logradouro", type="text", nullable=true)
	 */
	private $logradouro;

	/**
	 * @var int|null
	 *
	 * @ORM\Column(name="numero", type="integer", nullable=true)
	 */
	private $numero;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="complemento", type="text", nullable=true)
	 */
	private $complemento;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="userId", type="integer", nullable=false)
	 */
	private $userid;

	/*
   * Getters
   */
	public function getId()
	{
		return $this->id;
	}

	public function getCep()
	{
		return $this->cep;
	}

	public function getCidade()
	{
		return $this->cidade;
	}

	public function getBairro()
	{
		return $this->bairro;
	}

	public function getLogradouro()
	{
		return $this->logradouro;
	}

	public function getNumero()
	{
		return $this->numero;
	}

	public function getComplemento()
	{
		return $this->complemento;
	}

	public function getUserid()
	{
		return $this->userid;
	}

	/*
   * Setters
   */
	public function setId($p_id)
	{
		$this->id = $p_id;
	}

	public function setCep($p_cep)
	{
		$this->cep = $p_cep;
	}

	public function setCidade($p_cidade)
	{
		$this->cidade = $p_cidade;
	}

	public function setBairro($p_bairro)
	{
		$this->bairro = $p_bairro;
	}

	public function setLogradouro($p_logradouro)
	{
		$this->logradouro = $p_logradouro;
	}

	public function setNumero($p_numero)
	{
		$this->numero = $p_numero;
	}

	public function setComplemnto($p_complemento)
	{
		$this->complemento = $p_complemento;
	}

	public function setUserid($p_userid)
	{
		$this->userid = $p_userid;
	}
}
