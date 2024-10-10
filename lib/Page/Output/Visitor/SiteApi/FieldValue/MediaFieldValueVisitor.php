<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\SiteApi\FieldValue;

use Ibexa\Core\FieldType\Media\Value as MediaValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\Media\Value>
 */
final class MediaFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof MediaValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->id,
            'fileName' => $value->fileName,
            'fileSize' => $value->fileSize,
            'uri' => $value->uri,
            'inputUri' => $value->inputUri,
            'mimeType' => $value->mimeType,
            'width' => $value->width,
            'height' => $value->height,
            'autoplay' => $value->autoplay,
            'hasController' => $value->hasController,
            'loop' => $value->loop,
        ];
    }
}
