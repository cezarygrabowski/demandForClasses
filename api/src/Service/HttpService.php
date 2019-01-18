<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HttpService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

// all callback parameters are optional (you can omit the ones you don't use)
        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = array()) {
//            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
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

    public function createSuccessResponse() {
        return new JsonResponse("success");
    }
}