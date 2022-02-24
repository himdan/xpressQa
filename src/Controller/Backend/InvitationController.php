<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 04:23 م
 */

namespace App\Controller\Backend;


use App\Controller\QaController;
use App\Entity\QaInvitation;
use App\Form\InvitationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InvitationController
 * @package App\Controller\Backend
 * @Route("/admin/invitations")
 */
class InvitationController extends QaController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="invitation_index")
     */
    public function index()
    {
        return $this->render('backend/pages/invitation/index.html.twig', []);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/new", name="invitation_create", methods={"POST", "GET"}, options={"expose":"true"})
     */
    public function create(Request $request)
    {
        $invitation = new QaInvitation();
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('common/modal.html.twig', [
            'form' => $form->createView(),
            'action' => 'invitation_create'
        ]);
    }
}
