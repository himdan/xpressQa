<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 11:11 م
 */

namespace App\Component\Datatable;


use App\Component\Datatable\Manager\DatatableManager;
use App\Component\Datatable\Manager\DtSource;
use Symfony\Component\Serializer\SerializerInterface;

abstract class DatatableManagerFactory
{

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var DtSource
     */
    private $dataSource;


    /**
     * DatatableManagerFactory constructor.
     * @param SerializerInterface $serializer
     * @param DtSource $dataSource
     */
    public function __construct(SerializerInterface $serializer, DtSource $dataSource)
    {
        $this->serializer = $serializer;
        $this->dataSource = $dataSource;
    }

    /**
     * @return DatatableManager
     */
    public  function createDatatableManager()
    {
        $dtm = new DatatableManager();
        $dtm
            ->setSerializer($this->serializer)
            ->setDataSource($this->dataSource);
        return $dtm;
    }



}
