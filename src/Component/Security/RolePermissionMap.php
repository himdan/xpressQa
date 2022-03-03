<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 02/03/22
 * Time: 04:55 م
 */

namespace App\Component\Security;


use Symfony\Component\HttpFoundation\ParameterBag;

class RolePermissionMap extends ParameterBag
{
    private function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }

    public static function init($attributes = []){
        foreach ($attributes as $key=>$values){
            $attributes[$key] = is_array($values)?array_unique($values):$values;
        }
        return new static($attributes);
    }



}
