<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 03:19 م
 */

namespace App\Controller\Backend;


use App\Component\Provider\Github\GithubApiClientFactory;
use App\Controller\QaController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RemoteMembersController
 * @package App\Controller\Backend
 * @Route("/admin/remote_members")
 */
class RemoteMembersController extends QaController
{


    /**
     * @Route("/orgs", name="orgs", options={"expose":"true"}))
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganization(Request $request, GithubApiClientFactory $apiClientFactory)
    {
        return $this->json($apiClientFactory->configure()->currentUser()->organizations());
    }


    /**
     * @Route("/orgs/members", name="org_members", options={"expose":"true"})
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganizationMembers(Request $request, GithubApiClientFactory $apiClientFactory)
    {

        return $this->json($apiClientFactory->configure()->organization()->members());
    }
}
