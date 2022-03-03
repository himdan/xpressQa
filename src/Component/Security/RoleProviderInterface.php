<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 02/03/22
 * Time: 01:16 م
 */

namespace App\Component\Security;


interface RoleProviderInterface
{
    /**
     * @return array
     */
    public function getRoles():array;
}
