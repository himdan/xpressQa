<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 24/02/22
 * Time: 04:45 م
 */

namespace App\Manager;


use App\Entity\QaInvitation;
use Doctrine\ORM\EntityManagerInterface;

class InvitationManager
{

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * InvitationManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function createInvitation(array $emails)
    {
        foreach ($emails as $email){
            $invitation = new QaInvitation();
            $invitation->setEmail($email);
            $this->em->persist($email);

        }
        try{
            $this->em->flush();
        }catch (\Exception $exception){

        }

    }







}
