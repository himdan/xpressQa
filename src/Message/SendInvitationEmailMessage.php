<?php

namespace App\Message;

final class SendInvitationEmailMessage
{
    private $invitations = [];

    /**
     * SendInvitationEmailMessage constructor.
     * @param array $invitations
     */
    public function __construct(array $invitations)
    {
        $this->invitations = $invitations;
    }

    /**
     * @return array
     */
    public function getInvitations(): array
    {
        return $this->invitations;
    }



}
