<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 02:45 م
 */

namespace App\Datatable;


use App\Component\Datatable\DatatableManagerFactory;
use App\Repository\QaProviderRepository;
use Symfony\Component\Serializer\SerializerInterface;

class ProviderDatatable extends DatatableManagerFactory
{
    /**
     * UserDatatable constructor.
     * @param SerializerInterface $serializer
     * @param QaProviderRepository $repository
     */
    public function __construct(
        SerializerInterface $serializer,
        QaProviderRepository $repository
    )
    {
        parent::__construct($serializer, $repository);
    }
}
