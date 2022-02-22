<?php

namespace App\Repository;

use App\Component\Datatable\Manager\DtSource;
use App\Entity\QaUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method QaUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method QaUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method QaUser[]    findAll()
 * @method QaUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QaUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, DtSource
{

    use PaginatorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QaUser::class);
    }

    /**
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof QaUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function search(array $context): Paginator
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select("qa_user")
            ->from(QaUser::class, 'qa_user');

        return $this->getPaginator($qb, $context);
    }


}
