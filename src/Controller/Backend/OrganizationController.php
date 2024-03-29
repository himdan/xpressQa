<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 24/02/22
 * Time: 05:36 م
 */

namespace App\Controller\Backend;


use App\Component\Security\ACL;
use App\Controller\QaController;
use App\Entity\QaMembership;
use App\Entity\QaOrganization;
use App\Form\OrganizationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrganizationController
 * @package App\Controller\Backend
 * @Route("/admin/org")
 */
class OrganizationController extends QaController
{
    /**
     * @ACL(contextGroup={"ORG MANAGEMENT"})
     * @Route("/create", name="create_org", options={"expose":"true"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $organization = new QaOrganization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $em->persist($organization);
            $qaMemberShip = new QaMembership($this->getUser(), $organization);
            $em->persist($qaMemberShip);
            $em->flush();
        }
        return $this->render('backend/pages/organization/create.org.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('create_org')
        ]);

    }
}
