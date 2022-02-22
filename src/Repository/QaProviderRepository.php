<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 02:49 م
 */

namespace App\Repository;

use App\Component\Datatable\Manager\DtSource;
use App\Entity\QaProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class QaProviderRepository extends ServiceEntityRepository implements DtSource
{
    use PaginatorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QaProvider::class);
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
}
