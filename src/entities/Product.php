<?php
namespace App\Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
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
    private $createdat = 'datetime(\'now\')';

    /**
     * @var string
     *
     * @ORM\Column(name="updatedAt", type="text", nullable=false, options={"default"="datetime('now')"})
     */
    private $updatedat = 'datetime(\'now\')';

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


}
