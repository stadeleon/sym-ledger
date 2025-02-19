<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class BaseApiController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    protected function deserializeRequest(string $content, string $dtoClass)
    {
        return $this->serializer->deserialize($content, $dtoClass, 'json');
    }

    protected function formatValidationErrors($errors): array
    {
        return array_map(fn($error) => $error->getMessage(), iterator_to_array($errors));
    }
}