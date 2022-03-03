<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 02/03/22
 * Time: 05:15 م
 */

namespace App\Component\Security;


use App\Component\Security\Entity\PermissionMap;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionMapManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var AuthorizationCheckerInterface $ac
     */
    private $ac;
    /**
     * @var AclDiscoveryRegistry
     */
    private $aclDiscoveryRegistry;

    /**
     * PermissionMapManager constructor.
     * @param EntityManagerInterface $em
     * @param AuthorizationCheckerInterface $ac
     * @param AclDiscoveryRegistry $aclDiscoveryRegistry
     */
    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $ac, AclDiscoveryRegistry $aclDiscoveryRegistry)
    {
        $this->em = $em;
        $this->ac = $ac;
        $this->aclDiscoveryRegistry = $aclDiscoveryRegistry;
    }


    private function findOne()
    {
        $pm = $this
            ->em
            ->getRepository(PermissionMap::class)
            ->findOneBy([]);
        if ($pm instanceof PermissionMap) {
            return $pm;
        }

    }

    public function getMapping($roles = [], $permissions = [])
    {
        if ($current = $this->findOne()) {
            return self::sanitize($current->getMapping(), $roles, $permissions);
        }
        $roleMap = [];
        foreach ($roles as $key => $values) {
            $roleMap[$key] = [];
        }
        return $roleMap;
    }

    public function findOrCreate($availableMapping)
    {
        if ($current = $this->findOne()) {
            $current->setMapping($availableMapping);
            return $current;
        }
        $pm = new PermissionMap();
        $pm->setMapping($availableMapping);
        return $pm;

    }

    private static function sanitize(array $mapping, array $rolesHierarchy, array $permissions)
    {
        $roles = array_keys($rolesHierarchy);
        $roleMapping = [];
        foreach ($roles as $role) {
            if (!$role) {
                continue;
            }
            if (empty($roleMapping[$role])) {
                $roleMapping[$role] = [];
            }
            $roleMapping[$role] = isset($mapping[$role]) ? array_filter($mapping[$role], function ($permission) use ($permissions) {
                return in_array($permission, $permissions);
            }) : [];
        }
        return $roleMapping;
    }

    public function isRouteGranted($routeName, UserInterface $user = null)
    {
        $registeredRoutes = $this->aclDiscoveryRegistry->getACL()['permissions'];
        if (!in_array($routeName, $registeredRoutes)) {
            return true;
        }
        $pm = $this->findOne();

        if ($pm instanceof PermissionMap) {
            $mapping = $pm->getMapping();

            if (count($mapping) === 0) {
                return true;
            }
            foreach ($mapping as $role => $routes) {
                if ($this->ac->isGranted($role, $user)) {
                    $test = in_array($routeName, array_values($routes));
                    if ($test) {
                        return $test;
                    }
                }
            }

            return false;
        }

        return true;
    }

}
