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
use App\Entity\QaUser;
use App\Manager\InvitationManager;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param UserManager $userManager
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(
        $identifier,
        InvitationManager $invitationManager,
        Request $request,
        UserManager $userManager,
        EntityManagerInterface $em
    ){

        $invitation = $invitationManager->findInvitation($identifier);
        $user = new QaUser();
        $fb = $this->createFormBuilder($user, [
            'data_class'=> QaUser::class
        ]);
        $form = $fb
            ->add('password', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $userManager->createFromInvitation(
                $user,
                $invitation,
                $form->getData()->getPassword()
            );
            $invitationManager->changeStatus($invitation, QaInvitation::ACCEPTED);
            $em->flush();
        }

        return $this->render('public/process-email/verify.html.twig', [
            'form'=>$form->createView(),
            'invitation'=>$invitation
        ]);
    }
}
