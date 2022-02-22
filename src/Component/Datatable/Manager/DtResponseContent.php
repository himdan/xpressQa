<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 08:21 م
 */

namespace App\Component\Datatable\Manager;


use Symfony\Component\Serializer\Annotation\Groups;

class DtResponseContent
{
    /**
     * @var int|null
     * @Groups({"dt"})
     */
    private $draw;
    /**
     * @var int|null
     */
    private $recordsTotal = 0;
    /**
     * @var int|null
     * @Groups({"dt"})
     */
    private $recordsFiltered = 0;
    /**
     * @var array
     * @Groups({"dt"})
     */
    private $data = [];
    /**
     * @var array
     * @Groups({"dt"})
     */
    private $serializationContext = [];

    /**
     * @return int|null
     */
    public function getDraw(): ?int
    {
        return $this->draw;
    }

    /**
     * @param int|null $draw
     * @return DtResponseContent
     */
    public function setDraw(?int $draw): DtResponseContent
    {
        $this->draw = $draw;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRecordsTotal(): ?int
    {
        return $this->recordsTotal;
    }

    /**
     * @param int|null $recordsTotal
     * @return DtResponseContent
     */
    public function setRecordsTotal(?int $recordsTotal): DtResponseContent
    {
        $this->recordsTotal = $recordsTotal;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRecordsFiltered(): ?int
    {
        return $this->recordsFiltered;
    }

    /**
     * @param int|null $recordsFiltered
     * @return DtResponseContent
     */
    public function setRecordsFiltered(?int $recordsFiltered): DtResponseContent
    {
        $this->recordsFiltered = $recordsFiltered;
        return $this;
    }


    public function addItem($item)
    {
        array_push($this->data, $item);
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getSerializationContext(): array
    {
        return $this->serializationContext;
    }

    /**
     * @param array $serializationContext
     */
    public function setSerializationContext(array $serializationContext): void
    {
        $this->serializationContext = $serializationContext;
    }




}
