<?php


namespace App\Http\Responses;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;

class FractalResponse
{
    /**
     * @var Manager
     */
    private $manager;
    /**
     * @var SerializerAbstract
     */
    private $serializer;

    /**
     * FractalResponse constructor.
     * @param Manager $manager
     * @param SerializerAbstract $serializer
     */
    public function __construct(Manager $manager, SerializerAbstract $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->manager->setSerializer($serializer);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function item($data, TransformerAbstract $transformer)
    {
        return $this->createDataArray(new Item($data, $transformer));
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function collection($data, TransformerAbstract $transformer)
    {
        return $this->createDataArray(new Collection($data, $transformer));
    }

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    private function createDataArray(ResourceInterface $resource)
    {
        return $this->manager->createData($resource)->toArray();
    }
}