<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 02:17 م
 */

namespace App\Repository;


use App\Component\Datatable\Manager\DtSource;
use App\Entity\QaAccessToken;
use App\Entity\QaUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;


class QaAccessTokenRepository extends ServiceEntityRepository implements DtSource
{

    use PaginatorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QaAccessToken::class);
    }

    /**
     * @param array $context
     * @return Paginator
     */
    public function search(array $context): Paginator
    {
        $qb = $this->createQueryBuilder('qa_access_token');
        return $this->getPaginator($qb, $context);
    }

    /**
     * @param QaUser $user
     * @param string $providerName
     * @return QaAccessToken|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTokenByProviderAndUser(QaUser $user, string $providerName)
    {
        $qb = $this->createQueryBuilder('qa_access_token');
        $qb
            ->innerJoin('qa_access_token.user', 'qa_user')
            ->innerJoin('qa_access_token.provider', 'qa_provider');
        $qb
            ->where('qa_user.id=:user_id')
            ->andWhere('qa_provider.name=:provider_name');

        $qb
            ->setParameter('user_id', $user->getId())
            ->setParameter('provider_name', $providerName);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getSingleResult();

    }


}
