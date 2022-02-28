<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 27/02/22
 * Time: 12:59 م
 */

namespace App\Controller\Common;


use App\Controller\QaController;
use App\Entity\QaInvitation;
use App\Manager\InvitationManager;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProcessInvitationController
 * @package App\Controller\Common
 * @Route("/invitation")
 */
class ProcessInvitationController extends QaController
{

    /**
     * @Route("/verify/{identifier}", name="check_invitation")
     * @param $identifier
     * @param InvitationManager $invitationManager
     * @return mixed
     */
    public function  verify($identifier, InvitationManager $invitationManager)
    {
        $invitation = $invitationManager->findInvitation($identifier);
        return $this->render('public/process-email/verify.html.twig', []);
    }

    /**
     *  @Route("/checkout/{identifier}", name="checkout_invitation")
     * @param $identifier
     * @param InvitationManager $invitationManager
     * @return mixed
     */
    public function register($identifier, InvitationManager $invitationManager){

        return $this->render('public/process-email/verify.html.twig', []);
    }
}
