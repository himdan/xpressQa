<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 06:57 م
 */

namespace App\Controller\Backend;

use App\Component\Security\ACL;
use App\Controller\QaController;
use App\Datatable\UserDatatable;
use App\Entity\QaMembership;
use App\Entity\QaOrganization;
use App\Entity\QaUser;
use App\Form\MemberShipType;
use App\Form\UserProfileType;
use App\Form\UserType;
use App\Manager\UserSecurityProfileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserManagementController
 * @package App\Controller\Backend
 * @Route("/admin/users")
 */
class UserManagementController extends QaController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function index()
    {
        return $this->render('backend/pages/user-management/index.html.twig', []);
    }

    /**
     * @ACL(contextGroup={"USER MANAGEMENT"})
     * @param Request $request
     * @param UserDatatable $userDatatable
     * @return Response
     * @Route("/list", name="list_users", options={"expose":true})
     */
    public function search(Request $request, UserDatatable $userDatatable)
    {
        $context = $this->getContext($request);

        $datatable = $userDatatable->createDatatableManager();
        $content = $datatable->JsonSerialize($context, ['groups' => ['admin_user']]);
        $response = new Response($content,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
        return $response;


    }

    /**
     * @ACL(contextGroup={"USER MANAGEMENT"})
     * @Route("/manage/memberships/{user}", name="manage_memberships", options={"expose":"true"})
     * @param QaUser $user
     * @param Request $request
     * @return Response
     */
    public function updateSecurityProfile(QaUser $user, Request $request, EntityManagerInterface $em){

        $fb = $this->createFormBuilder($user, ['data_class' => QaUser::class]);
        $fb->add('memberShip', CollectionType::class, [
            'entry_type' => MemberShipType::class
        ]);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if($request->isMethod('Post') && $form->isSubmitted() && $form->isValid()){
            $em->flush();
        }
        return $this->render('backend/pages/user-management/memberships.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'action' => $this->generateUrl('manage_memberships', [
                'user' => $user->getId()
            ])
        ]);

    }
    /**
     *
     * @Route("/manage/{organization}/member/{user}", name="manage_org_member", options={"expose":"true"})
     * @param QaOrganization $organization
     * @param QaUser $user
     * @param UserSecurityProfileManager $securityProfileManager
     * @param Request $request
     * @return Response
     */
    public function updateOrgSecurityProfile(
        QaOrganization $organization,
        QaUser $user,
        UserSecurityProfileManager $securityProfileManager,
        Request $request
    )
    {
        $profile = $securityProfileManager->createOrgProfile($user, $organization);
        $form = $this->createForm(UserProfileType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('backend/pages/user-management/profile.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
            'action' => $this->generateUrl('manage_org_member', [
                'organization' => $organization->getId(),
                'user' => $user->getId()
            ])
        ]);

    }
}
