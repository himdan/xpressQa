<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 23/02/22
 * Time: 06:06 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaOrganization
 * @package App\Entity
 * @ORM\Entity()
 */
class QaOrganization
{
    use TenantTrait;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $domain;
    /**
     * @var string|null
     */
    private $name;

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     */
    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

}
