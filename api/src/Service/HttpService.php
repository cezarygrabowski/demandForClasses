<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HttpService
{
    private $serializer;
    private $tokenStorage;

    public function __construct(
        TokenStorageInterface $storage,
        SerializerInterface $serializer
    ) {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

// all callback parameters are optional (you can omit the ones you don't use)
        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = array()) {
//            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $this->serializer = $serializer;
        $this->tokenStorage = $storage;
    }

    public function serializeCollection($items)
    {
        $items = $this->serializer->serialize($items, 'json');
        return $items;
    }

    public function createCollectionResponse($items): Response
    {
        $items = $this->serializer->serialize($items, 'json');

        return new Response($items);
    }

    public function createItemResponse($demand): Response
    {

        $demand = $this->serializer->serialize($demand, 'json');

        return new Response($demand);
    }

    public function createSuccessResponse(): JsonResponse
    {
        return new JsonResponse("success");
    }

    public function getCurrentUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
            return $user;
        } else {
            return null;
        }
    }

    public function downloadCsvFileResponse(StreamedResponse $response, string $fileName): StreamedResponse
    {
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename=' . $fileName .'\'');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response->sendHeaders()->sendContent();
    }

    public function prepareStreamedResponse(array $results): StreamedResponse
    {
        return new StreamedResponse(function() use ($results) {
            $handle = fopen('php://output', 'r+');

            foreach ($results as $result) {
                fputcsv($handle, $result->toArray());
            }

            fclose($handle);
        });
    }

    public function readCsvContent(string $file, bool $removeHeader)
    {
        $file = fopen($file, 'r');
        $data = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            $data[] = $line;
        }
        fclose($file);

        /** remove first element which is header */
        if($removeHeader) {
            array_shift($data);
        }

        return $data;
    }


}