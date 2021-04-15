<?php



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


}
