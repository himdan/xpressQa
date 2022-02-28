<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 05:35 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class QaContact
 * @package App\Entity
 * @ORM\Entity()
 */
class QaContact
{
    use TenantTrait;
    /**
     * @var QaUser|null $owner
     * @ORM\ManyToOne(targetEntity=QaUser::class)
     */
    private $owner;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"admin_user"})
     */
    private $name;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"admin_user"})
     */
    private $email;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Groups({"admin_user"})
     */
    private $picture;

    /**
     * QaContact constructor.
     * @param QaUser|null $owner
     */
    public function __construct(?QaUser $owner)
    {
        $this->owner = $owner;
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
     * @return QaContact
     */
    public function setName(?string $name): QaContact
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return QaContact
     */
    public function setEmail(?string $email): QaContact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     * @return QaContact
     */
    public function setPicture(?string $picture): QaContact
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @return QaUser|null
     */
    public function getOwner(): ?QaUser
    {
        return $this->owner;
    }

    /**
     * @param QaUser|null $owner
     * @return QaContact
     */
    public function setOwner(?QaUser $owner): QaContact
    {
        $this->owner = $owner;
        return $this;
    }


}
