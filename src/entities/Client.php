<?php



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


}
