<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 05:26 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaInvitation
 * @package App\Entity
 * @ORM\Entity()
 */
class QaInvitation
{
    use TenantTrait;
    const Pending = 1;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;
    /**
     * @var \DateTimeInterface $createdAt
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var int|null
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;
    /**
     * @var QaOrganization|null $organization
     * @ORM\ManyToOne(targetEntity=QaOrganization::class)
     */
    private $qaOrganization;

    /**
     * QaInvitation constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->status = self::Pending;
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
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return QaOrganization|null
     */
    public function getQaOrganization(): ?QaOrganization
    {
        return $this->qaOrganization;
    }

    /**
     * @param QaOrganization|null $qaOrganization
     */
    public function setQaOrganization(?QaOrganization $qaOrganization): void
    {
        $this->qaOrganization = $qaOrganization;
    }





}
