<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 02/03/22
 * Time: 01:15 م
 */

namespace App\Component\Security;


use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

abstract class RoleProvider implements RoleProviderInterface
{

    /**
     * @var ContainerBagInterface
     */
    private $containerBag;

    /**
     * RoleProvider constructor.
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
    }

    public function getRoles(): array
    {
        if(count($this->containerBag->get('security.role_hierarchy.roles')) !== 0){
            return $this->containerBag->get('security.role_hierarchy.roles');
        }
        return ['ROLE_USER'=>['ROLE_USER']];
    }

}
