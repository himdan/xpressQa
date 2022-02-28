<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 12:34 م
 */

namespace App\Repository;
use App\Component\Datatable\Manager\DtSource;
use App\Entity\QaInvitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class QaInvitationRepository extends ServiceEntityRepository implements DtSource
{
    use PaginatorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QaInvitation::class);
    }

    /**
     * @param array $context
     * @return Paginator
     */
    public function search(array $context): Paginator
    {
        $qb = $this->createQueryBuilder('qa_invitation');
        return $this->getPaginator($qb, $context);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function getPendingInvitationByIds(array  $ids=[]){
        $qb = $this->createQueryBuilder('qa_invitation');
        $qb
            ->where('qa_invitation in(:ids)')
            ->andWhere('qa_invitation.status=:status')
            ->setParameter('status', QaInvitation::Pending)
            ->setParameter('ids', $ids)
        ;
        return $qb->getQuery()->getResult();
    }

}
