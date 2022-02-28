<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 28/02/22
 * Time: 11:09 ص
 */

namespace App\Mailer;


use App\Component\Mailer\QaMailer;
use App\Entity\QaInvitation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class InvitationMailer extends QaMailer
{
    /**
     * @var Environment $twig
     */
    private $twig;
    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private $urlGenerator;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator)
    {
        parent::__construct($mailer);
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param QaInvitation $invitation
     */
    public function sendInvitation(QaInvitation $invitation)
    {
        $this
            ->setMailComposer(function (Email $email) use ($invitation) {
                $link = $this->urlGenerator->generate('check_invitation', [
                    'identifier' => $invitation->getIdentifier()
                ], UrlGenerator::ABSOLUTE_URL);
                $email
                    ->from('noReply@XpressQa.com')
                    ->to($invitation->getEmail())
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('New Invitation from XpressQa.com')
                    ->text(sprintf('Hey You have be invited to join the %s workspace please flow this url %s',
                        $invitation->getQaOrganization()->getName(),
                        $link

                    ))
                    ->html($this->twig->render('common/invitation.html.twig', [
                        'organizationName' => $invitation->getQaOrganization()->getName(),
                        'link' => $link
                    ]));
            })->send();
    }

}
