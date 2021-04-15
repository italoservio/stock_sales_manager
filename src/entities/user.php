<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_name_IDX", columns={"name"})}, indexes={@ORM\Index(name="user_id_IDX", columns={"id", "login", "name", "pass"})})
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
     * @ORM\Column(name="login", type="text", length=64, nullable=false)
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
    private $createdat = 'datetime(\'now\')';

    /**
     * @var string|null
     *
     * @ORM\Column(name="updatedAt", type="text", nullable=true, options={"default"="datetime('now')"})
     */
    private $updatedat = 'datetime(\'now\')';


}
