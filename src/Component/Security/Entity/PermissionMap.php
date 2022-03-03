<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 02/03/22
 * Time: 05:03 م
 */

namespace App\Component\Security\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PermissionMap
 * @package App\Component\Security\Entity
 * @ORM\Entity()
 */
class PermissionMap
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    private $mapping;

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

    /**
     * @return array
     */
    public function getMapping(): array
    {
        foreach ($this->mapping as $key =>$values){
            $this->mapping[$key] = is_array($values)?array_values($values): $values;
        }
        return $this->mapping;
    }

    /**
     * @param array $mapping
     */
    public function setMapping(array $mapping): void
    {
        foreach ($this->mapping as $key =>$values){
            $this->mapping[$key] = is_array($values)?array_values($values): $values;
        }
        $this->mapping = $mapping;
    }

}
