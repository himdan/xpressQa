<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 01:17 ص
 */
namespace App\Manager;

use App\Entity\QaUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager
{
    /**
     * @var \App\Repository\QaUserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordHasherInterface $passwordHash
     */
    private $passwordHash;
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * UserManager constructor.
     * @param \App\Repository\QaUserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHash
     */
    public function __construct(\App\Repository\QaUserRepository $userRepository, UserPasswordHasherInterface $passwordHash, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->passwordHash = $passwordHash;
        $this->em = $em;
    }


    /**
     * @param $identifier
     * @return QaUser|null
     */
    public function findCreateOrUser($identifier)
    {
        $currentUser = $this->userRepository->findOneBy(['email' => $identifier]);
        if($currentUser) return $currentUser;
        $currentUser = new QaUser();
        $currentUser->setEmail($identifier);
        return $currentUser;
    }

    public function processFully($identifier, $password)
    {
        $currentUser = $this->findCreateOrUser($identifier);
        $currentUser->setPassword($this->passwordHash->hashPassword($currentUser, $password));
        return $currentUser;

    }

    public function seedUser(QaUser $qaUser)
    {
        $this->em->persist($qaUser);
        $this->em->flush();
    }
}
