<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 03:39 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QaProviderRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class QaProvider
 * @package App\Entity
 * @ORM\Entity(repositoryClass=QaProviderRepository::class)
 */
class QaProvider
{

    use TenantTrait;
    /**
     * @ORM\Column(type="string")
     * @var string|null
     * @Groups({"all"})
     */
    private $name;

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
