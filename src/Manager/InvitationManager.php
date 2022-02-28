<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 27/02/22
 * Time: 10:50 ص
 */

namespace App\Manager;


use App\Entity\QaInvitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InvitationManager
{
    /**
     * @var ParameterBagInterface $parameterBag
     */
    private $parameterBag;
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * InvitationManager constructor.
     * @param ParameterBagInterface $parameterBag
     * @param EntityManagerInterface $em
     */
    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $em)
    {
        $this->parameterBag = $parameterBag;
        $this->em = $em;
    }


    /**
     * @param QaInvitation $invitation
     * @return string
     */
    public function generateIdentifier(QaInvitation $invitation)
    {
        $meta = [
            'email' => $invitation->getEmail(),
            'time' => microtime(),
            'secret' =>'XpressQa-dev'
        ];
        $claim = json_encode($meta);
        return sha1($claim);

    }

    public function findInvitation($identifier)
    {
        return $this
            ->em
            ->getRepository(QaInvitation::class)
            ->findOneBy(['identifier' => $identifier]);
    }

    public function changeStatus(QaInvitation $invitation, int $status)
    {
        $invitation->setStatus($status);
        return $this;
    }

    public function flush()
    {
        $this->em->flush();
    }
}
