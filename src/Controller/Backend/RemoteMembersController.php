<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 03:19 م
 */

namespace App\Controller\Backend;


use App\Component\Provider\Github\GithubApiClientFactory;
use App\Component\Security\ACL;
use App\Controller\QaController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RemoteMembersController
 * @package App\Controller\Backend
 * @Route("/admin/github")
 */
class RemoteMembersController extends QaController
{


    /**
     * @ACL(contextGroup={"INVITATION MANAGEMENT"})
     * @Route("/orgs", name="list_remote_orgs", options={"expose":"true"}))
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganization(Request $request, GithubApiClientFactory $apiClientFactory)
    {
        return $this->json(
            $apiClientFactory
                ->configure()
                ->currentUser()
                ->organizations()
        );
    }


    /**
     * @ACL(contextGroup={"INVITATION MANAGEMENT"})
     * @Route("/orgs/members", name="list_org_members", options={"expose":"true"})
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganizationMembers(Request $request, GithubApiClientFactory $apiClientFactory)
    {

        $org = $request->get('org', 'MehdiDemo-Organization');
        return $this->json($apiClientFactory
            ->configure()
            ->organization()
            ->members()
            ->all($org)
        );
    }
    /**
     * @Route("/orgs/projects", name="list_org_project", options={"expose":"true"})
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganizationProject(Request $request, GithubApiClientFactory $apiClientFactory)
    {
        $org = $request->get('org', 'MehdiDemo-Organization');
        return $this->json($apiClientFactory
            ->configure()
            ->orgProjects()
            ->all($org)
        );
    }
    /**
     * @Route("/orgs/repositories", name="list_org_repositories", options={"expose":"true"})
     * @param Request $request
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listOrganizationRepositories(Request $request, GithubApiClientFactory $apiClientFactory){
        $org = $request->get('org', 'MehdiDemo-Organization');
        return $this->json($apiClientFactory
            ->configure()
            ->organization()
            ->repositories($org)
        );
    }

    /**
     * @Route("/me/repositories", name="list_my_repositories", options={"expose":"true"})
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listMyRepositories(GithubApiClientFactory $apiClientFactory){
        return $this->json($apiClientFactory
            ->configure()
            ->me()
            ->repositories('all')
        );
    }

    /**
     * @Route("/me/tree", name="view_my_github_tree", options={"expose":"true"})
     * @param GithubApiClientFactory $apiClientFactory
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadTree(GithubApiClientFactory $apiClientFactory){
        $myRepositories = $apiClientFactory
            ->configure()
            ->me()
            ->repositories('all');
        $repositoryMapper = function ($repository){
            $repository['type'] = 'repository';
            return [
                'name' => $repository['name'],
                'data' => $repository,
            ];
        };
        $organizations = $apiClientFactory
            ->configure()
            ->currentUser()
            ->organizations();
        $projectMapper = function ($project){
            $project['type'] = 'project';
            return [
                'name' =>  $project['name'],
                'data' => $project,
            ];
        };
        $projectByOrgMapper = function ($org)use($apiClientFactory, $projectMapper){
            $projects = $apiClientFactory
                ->configure()
                ->orgProjects()
                ->all($org);
            return array_map($projectMapper, $projects);
        };
        $orgMapper = function ($org)use($projectByOrgMapper){
            $org['type'] = 'organization';
            $org['name'] = $org['login'];
            return [
                'name' => $org['login'],
                'data' => $org,
                'children' => [
                    [
                        'name' => 'projects',
                        'children' => $projectByOrgMapper($org['login'])
                    ]
                ]
            ];
        };
        $tree = [
            'providers' =>[
                [
                    'name' => 'Github',
                    'children' =>  [
                        [
                            'name' => 'organizations',
                            'children'=> array_map($orgMapper, $organizations)
                        ],
                        [
                            'name' => 'Personal',
                            'children' => [
                                [
                                    'name' => 'repositories',
                                    'children' => array_map($repositoryMapper, $myRepositories)
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];

        return $this->json($tree);
    }
}
