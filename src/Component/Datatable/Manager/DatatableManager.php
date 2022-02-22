<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 08:18 م
 */

namespace App\Component\Datatable\Manager;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 * Class DatatableManager
 * @package App\Component\Datatable\Manager
 */
class DatatableManager
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
     * @param SerializerInterface $serializer
     * @return DatatableManager
     */
    public function setSerializer(SerializerInterface $serializer): DatatableManager
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @param DtSource $dataSource
     * @return DatatableManager
     */
    public function setDataSource(DtSource $dataSource): DatatableManager
    {
        $this->dataSource = $dataSource;
        return $this;
    }




    /**
     * @param array $context
     * @return DtResponseContent
     */
    private function wrap(array $context): DtResponseContent
    {
        $dtResponseContent = new DtResponseContent();
        $paginator = $this->dataSource->search($context);
        $count = count($paginator);
        $dtResponseContent
            ->setDraw(1)
            ->setRecordsTotal($count)
            ->setRecordsFiltered($count);
        foreach ($paginator as $item) {
            $dtResponseContent->addItem($item);
        }
        return $dtResponseContent;
    }

    /**
     * @param array $context
     * @param array $normalizationContext
     * @return string
     */
    public function JsonSerialize(array $context, array $normalizationContext = [])
    {
        $dtResponse = $this->wrap($context);
        $normalization = [];
        $normalization['groups'] =  isset($normalizationContext['groups'])? array_merge(['dt'], $normalizationContext['groups']):['dt'];
        $dtResponse->setSerializationContext($normalization);
        return $this->serializer->serialize($dtResponse, 'json', $normalization);

    }


}
