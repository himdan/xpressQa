<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 01/03/22
 * Time: 09:29 م
 */

namespace App\Component\Security\Controller;


use App\Component\Security\AclDiscoveryRegistry;
use App\Component\Security\PermissionMapManager;
use App\Component\Security\RolePermissionMap;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DiscoveryController extends AbstractController
{
    /**
     * @param AclDiscoveryRegistry $discoveryRegistry
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public  function listAcl(AclDiscoveryRegistry $discoveryRegistry)
    {
        return $this->json($discoveryRegistry->getACL());
    }

    /**
     * @param Request $request
     * @param PermissionMapManager $permissionMapManager
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function  index(
        Request $request,
        PermissionMapManager $permissionMapManager,
        EntityManagerInterface $em){
        if($request->isMethod('POST')){
            $matrix = RolePermissionMap::init($request->request->all());
            $mapping = $permissionMapManager->findOrCreate($matrix->all());
            $em->persist($mapping);
            $em->flush();
        }
        return $this->render('common/acl.html.twig', []);
    }
}
