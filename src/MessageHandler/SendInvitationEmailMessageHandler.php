<?php

namespace App\MessageHandler;

use App\Entity\QaInvitation;
use App\Mailer\InvitationMailer;
use App\Manager\InvitationManager;
use App\Message\SendInvitationEmailMessage;
use App\Repository\QaInvitationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendInvitationEmailMessageHandler implements MessageHandlerInterface
{

    /**
     * @var QaInvitationRepository
     */
    private $invitationRepository;
    /**
     * @var InvitationMailer $invitationMailer
     */
    private $invitationMailer;
    /**
     * @var InvitationManager $invitationManager
     */
    private $invitationManager;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * SendInvitationEmailMessageHandler constructor.
     * @param QaInvitationRepository $invitationRepository
     * @param InvitationMailer $invitationMailer
     * @param InvitationManager $invitationManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        QaInvitationRepository $invitationRepository,
        InvitationMailer $invitationMailer,
        InvitationManager $invitationManager,
        LoggerInterface $logger
    )
    {
        $this->invitationRepository = $invitationRepository;
        $this->invitationMailer = $invitationMailer;
        $this->invitationManager = $invitationManager;
        $this->logger = $logger;
    }


    public function __invoke(SendInvitationEmailMessage $message)
    {
        $invitations = $this->invitationRepository->getPendingInvitationByIds($message->getInvitations());
        foreach ($invitations as $invitation){
            if($invitation instanceof QaInvitation){
                try{
                    $this->send($invitation);
                    $this->invitationManager->changeStatus($invitation, QaInvitation::SENT);
                } catch (\Exception $exception){
                    $this->logger->error(sprintf('unhandled Invitation of  id %s %s', $invitation->getId(), $exception->getMessage()));
                }
            }
        }
    }
    private function send(QaInvitation $invitation)
    {
        $this->invitationMailer->sendInvitation($invitation);
    }
}
