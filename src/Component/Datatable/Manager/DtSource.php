<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 08:25 م
 */

namespace App\Component\Datatable\Manager;


use Doctrine\ORM\Tools\Pagination\Paginator;

interface DtSource
{
    public function search(array $context):Paginator;
}
