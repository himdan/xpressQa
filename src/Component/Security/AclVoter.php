<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 04/03/22
 * Time: 08:18 م
 */

namespace App\Component\Security;


use App\Entity\QaUser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AclVoter extends Voter
{

    const CAN_ACCESS_ROUTE = 'ACCESS_ROUTE';

    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;
    /**
     * @var PermissionMapManager $pmm
     */
    private $pmm;

    /**
     * AclVoter constructor.
     * @param RequestStack $requestStack
     * @param PermissionMapManager $pmm
     */
    public function __construct(RequestStack $requestStack, PermissionMapManager $pmm)
    {
        $this->requestStack = $requestStack;
        $this->pmm = $pmm;
    }


    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::CAN_ACCESS_ROUTE])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof QaUser) {
            // the user must be logged in; if not, deny access
            return false;
        }
        return $this->pmm->isRouteGranted($this->getCurrentRoute(), $user);
    }

    private function getCurrentRoute(){
        return $this->requestStack->getCurrentRequest()->attributes->get('_route');
    }
}
