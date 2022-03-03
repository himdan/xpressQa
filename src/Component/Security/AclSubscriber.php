<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 10:15 ص
 */

namespace App\Component\Security;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AclSubscriber implements EventSubscriberInterface
{

    /**
     * @var PermissionMapManager
     */
    private $pmm;
    /**
     * @var Security
     */
    private $security;

    /**
     * AclSubscriber constructor.
     * @param PermissionMapManager $pmm
     * @param Security $security
     */
    public function __construct(PermissionMapManager $pmm, Security $security)
    {
        $this->pmm = $pmm;
        $this->security = $security;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelController',
        ];
    }

    public function onKernelController(RequestEvent $event){
        $route = $event->getRequest()->attributes->get('_route');
        if(!$this->pmm->isRouteGranted($route, $this->security->getUser())){
            throw new AccessDeniedHttpException('You dont have permission to access this action');
        }

    }

}
