<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 03:40 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait TenantTrait
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
