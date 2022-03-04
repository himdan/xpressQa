<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 04/03/22
 * Time: 03:16 م
 */

namespace App\Menu;


use App\Component\Menu\MenuBuilder;
use App\Component\Security\PermissionMapManager;
use Symfony\Component\Security\Core\Security;

class BackMenuBuilder extends MenuBuilder
{
    /**
     * @var PermissionMapManager $pmm
     */
    private  $pmm;
    /**
     * @var Security $security
     */
    private $security;

    /**
     * @param PermissionMapManager $pmm
     */
    public function setPmm(PermissionMapManager $pmm): void
    {
        $this->pmm = $pmm;
    }

    /**
     * @param Security $security
     */
    public function setSecurity(Security $security): void
    {
        $this->security = $security;
    }




    protected function isEnabled($menuItem): bool
    {
        return isset($menuItem['route'])?$this->pmm->isRouteGranted($menuItem['route'], $this->security->getUser()):true;

    }
}
