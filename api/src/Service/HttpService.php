<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class HttpService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createCollectionResponse($items){
        $items = $this->serializer->serialize($items, 'json');

        return new Response($items);
    }

    public function createItemResponse($demand) {
        $demand = $this->serializer->serialize($demand, 'json');

        return new Response($demand);
    }
}