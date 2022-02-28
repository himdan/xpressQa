<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 10:11 ص
 */

namespace App\Model;


use App\Entity\QaOrganization;

class InvitationCollection
{
    /**
     * @var string[]
     */
    private $emails = [];
    /**
     * @var QaOrganization|null
     */
    private $organization;

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @param string[] $emails
     * @return InvitationCollection
     */
    public function setEmails(array $emails): InvitationCollection
    {
        $this->emails = $emails;
        return $this;
    }

    /**
     * @return QaOrganization|null
     */
    public function getOrganization(): ?QaOrganization
    {
        return $this->organization;
    }

    /**
     * @param QaOrganization|null $organization
     */
    public function setOrganization(?QaOrganization $organization): void
    {
        $this->organization = $organization;
    }



}
