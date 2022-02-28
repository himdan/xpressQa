<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 04:23 م
 */

namespace App\Controller\Backend;


use App\Controller\QaController;
use App\Datatable\InvitationDatatable;
use App\Entity\QaInvitation;
use App\Form\InvitationCollectionType;
use App\Manager\InvitationManager;
use App\Message\SendInvitationEmailMessage;
use App\Model\InvitationCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
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
     * @Route("/new", name="invitation_create", methods={"POST", "GET"}, options={"expose":"true"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param InvitationManager $invitationManager
     * @param MessageBusInterface $bus
     * @return Response
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        InvitationManager $invitationManager,
        MessageBusInterface $bus
    )
    {
        $invitation = new InvitationCollection();
        $emails = implode(',',isset($request->request->all()['invitation_collection'])?$request->request->all()['invitation_collection']['emails']:[]);
        $form = $this->createForm(InvitationCollectionType::class, $invitation, ['emails' => $emails]);
        $logger->info(sprintf('mails %s', json_encode($request->request->all())));
        $invitations = [];
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            foreach ($invitation->getEmails() as $email) {
                $qaInvitation = new QaInvitation();
                $qaInvitation->setEmail($email);
                $qaInvitation->setQaOrganization($invitation->getOrganization());
                $qaInvitation->setIdentifier($invitationManager->generateIdentifier($qaInvitation));
                $em->persist($qaInvitation);
                array_push($invitations, $qaInvitation);
            }
            $em->flush();
            $invitationsIds = array_map(function (QaInvitation $invitation){
                return $invitation->getId();
            }, $invitations);
            $message = new SendInvitationEmailMessage($invitationsIds);
            $bus->dispatch($message);

        }
        return $this->render('common/modal.html.twig', [
            'form' => $form->createView(),
            'action' => 'invitation_create'
        ]);
    }

    /**
     * @Route("/list", name="list_invitation", options={"expose":true})
     * @param Request $request
     * @param InvitationDatatable $qaInvitationDatatable
     * @return Response
     */
    public function search(Request $request, InvitationDatatable $qaInvitationDatatable)
    {
        $context = $this->getContext($request);

        $datatable = $qaInvitationDatatable->createDatatableManager();
        $content = $datatable->JsonSerialize($context, ['groups' => ['admin_user']]);
        $response = new Response($content,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
        return $response;


    }
}
