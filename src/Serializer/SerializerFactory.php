<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 11:07 ص
 */

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerFactory
{

    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetaDataFactory;

    /**
     * SerializerFactory constructor.
     * @param ClassMetadataFactoryInterface $classMetaDataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $classMetaDataFactory)
    {
        $this->classMetaDataFactory = $classMetaDataFactory;
    }


    /**
     * @return Serializer
     */
    public function createSerializer()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($this->classMetaDataFactory)];
        return new Serializer($normalizers, $encoders);
    }
}
