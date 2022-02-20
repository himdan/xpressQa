<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 21/02/22
 * Time: 12:20 ص
 */

namespace App\Datatable;


use App\Component\Datatable\DatatableManagerFactory;
use App\Repository\QaUserRepository;
use Symfony\Component\Serializer\SerializerInterface;

class UserDatatable extends DatatableManagerFactory
{
    /**
     * UserDatatable constructor.
     * @param SerializerInterface $serializer
     * @param QaUserRepository $qaUserRepository
     */
    public function __construct(
        SerializerInterface $serializer,
        QaUserRepository $qaUserRepository
    )
    {
        parent::__construct($serializer, $qaUserRepository);
    }



}
