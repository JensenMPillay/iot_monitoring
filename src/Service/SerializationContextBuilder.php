<?php

namespace App\Service;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

/**
 * Context builder for serialization.
 */
class SerializationContextBuilder
{
    /**
     * Builds default context for serialization.
     *
     * @return array
     */
    public function buildContext(): array
    {
        return (new ObjectNormalizerContextBuilder())
            ->withEnableMaxDepth(true)
            ->withPreserveEmptyObjects(true)
            ->withCircularReferenceHandler(function (object $object): string {
                return $object->getId();
            })
            ->toArray();
    }
}
