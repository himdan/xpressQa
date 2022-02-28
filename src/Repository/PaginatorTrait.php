<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 02:21 م
 */

namespace App\Repository;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginatorTrait
{

    /**
     * @param QueryBuilder $qb
     * @param $context
     * @return Paginator
     */
    protected function getPaginator(QueryBuilder $qb, $context){
        $itemPerPage = (int)($context['length'] ?? 10);
        $start = (int)($context['start'] ?? 10);

        $qb
            ->setFirstResult($start)
            ->setMaxResults($itemPerPage);
        $q = $qb->getQuery();
        $paginator = new Paginator($q);
        return $paginator;
    }
}
