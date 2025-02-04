<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Netgen\RemoteMediaIbexa\FieldType\Value as RemoteMediaValue;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\RemoteMediaIbexa\FieldType\Value>
 */
final class RemoteMediaFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof RemoteMediaValue;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $resourceLocation = $value->remoteResourceLocation;
        $remoteResource = $resourceLocation?->getRemoteResource();

        return [
            'remoteId' => $remoteResource?->getRemoteId(),
            'type' => $remoteResource?->getType(),
            'url' => $remoteResource?->getUrl(),
            'md5' => $remoteResource?->getMd5(),
            'id' => $remoteResource?->getId(),
            'name' => $remoteResource?->getName(),
            'originalFilename' => $remoteResource?->getOriginalFilename(),
            'version' => $remoteResource?->getVersion(),
            'visibility' => $remoteResource?->getVisibility(),
            'folder' => $remoteResource?->getFolder()?->getPath(),
            'size' => $remoteResource?->getSize(),
            'altText' => $remoteResource?->getAltText(),
            'caption' => $remoteResource?->getCaption(),
            'tags' => $remoteResource?->getTags(),
            'metadata' => $remoteResource?->getMetadata(),
            'context' => $remoteResource?->getContext(),
        ];
    }
}
