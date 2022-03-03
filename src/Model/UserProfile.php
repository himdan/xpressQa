<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 01:48 م
 */

namespace App\Model;


use App\Entity\QaMembership;
use App\Entity\QaOrganization;
use App\Entity\QaUser;
use Doctrine\Common\Collections\ArrayCollection;

class UserProfile
{
    /**
     * @var QaUser $user
     */
    private $user;
    /**
     * @var QaMembership[]
     */
    private $memberShip;
    /**
     * @var QaOrganization $organization
     */
    private $organization;
    /**
     * @var QaMembership
     */
    private $activeMemberShip;

    /**
     * UserProfile constructor.
     * @param QaUser $user
     * @param QaOrganization $organization
     */
    public function __construct(QaUser $user, QaOrganization $organization)
    {
        $this->user = $user;
        $this->organization = $organization;
        $this->memberShip = $user->getMembership()->toArray();
    }

    /**
     * @return QaUser
     */
    public function getUser(): QaUser
    {
        return $this->user;
    }

    /**
     * @param QaUser $user
     */
    public function setUser(QaUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return QaMembership
     */
    public function getActiveMemberShip(): QaMembership
    {
        $memberships = array_filter($this->memberShip, function(QaMembership $membership){
            if(!$membership->getQaOrg() instanceof QaOrganization){
                return false;
            }
            return $membership->getQaOrg()->getId() === $this->organization->getId();
        });
        $current = (new ArrayCollection($memberships))->first();
        return $current!==false?$current:new QaMembership($this->user, $this->organization);
    }

    /**
     * @param QaMembership $activeMemberShip
     */
    public function setActiveMemberShip(QaMembership $activeMemberShip): void
    {
        $this->activeMemberShip = $activeMemberShip;
    }






}
