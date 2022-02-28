<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 12:33 م
 */

namespace App\Datatable;

use App\Component\Datatable\DatatableManagerFactory;
use App\Repository\QaInvitationRepository;
use Symfony\Component\Serializer\SerializerInterface;

class InvitationDatatable extends DatatableManagerFactory
{
    /**
     * UserDatatable constructor.
     * @param SerializerInterface $serializer
     * @param QaInvitationRepository $repository
     */
    public function __construct(
        SerializerInterface $serializer,
        QaInvitationRepository $repository
    )
    {
        parent::__construct($serializer, $repository);
    }
}
