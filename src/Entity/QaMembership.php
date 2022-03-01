<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 23/02/22
 * Time: 06:09 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaMembership
 * @package App\Entity
 * @ORM\Entity()
 */
class QaMembership
{
    use  TenantTrait;
    /**
     * @var QaUser|null
     * @ORM\ManyToOne(targetEntity=QaUser::class, inversedBy="memberShip")
     */
    private $qaUser;
    /**
     * @var QaOrganization|null
     * @ORM\ManyToOne(targetEntity=QaOrganization::class)
     */
    private $qaOrg;
    /**
     * @var array $permissions
     * @ORM\Column(type="json", nullable=true)
     */
    private $permissions;

    /**
     * QaMembership constructor.
     * @param QaUser|null $qaUser
     * @param QaOrganization|null $qaOrg
     */
    public function __construct(?QaUser $qaUser, ?QaOrganization $qaOrg)
    {
        $this->qaUser = $qaUser;
        $this->qaOrg = $qaOrg;
        $this->permissions = ['GUEST'=>[]];
    }

    /**
     * @return QaUser|null
     */
    public function getQaUser(): ?QaUser
    {
        return $this->qaUser;
    }

    /**
     * @param QaUser|null $qaUser
     */
    public function setQaUser(?QaUser $qaUser): void
    {
        $this->qaUser = $qaUser;
    }

    /**
     * @return QaOrganization|null
     */
    public function getQaOrg(): ?QaOrganization
    {
        return $this->qaOrg;
    }

    /**
     * @param QaOrganization|null $qaOrg
     */
    public function setQaOrg(?QaOrganization $qaOrg): void
    {
        $this->qaOrg = $qaOrg;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }




}
