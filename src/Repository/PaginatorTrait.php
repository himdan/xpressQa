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
        $itemPerPage = (int)($context['itemPerPage'] ?? 10);
        $page = (int)($context['page'] ?? 0);

        $qb
            ->setFirstResult($page * $itemPerPage)
            ->setMaxResults($itemPerPage);
        $q = $qb->getQuery();
        $paginator = new Paginator($q);
        return $paginator;
    }
}
