<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 01/03/22
 * Time: 09:29 م
 */

namespace App\Component\Security\Controller;


use App\Component\Security\AclDiscoveryRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
