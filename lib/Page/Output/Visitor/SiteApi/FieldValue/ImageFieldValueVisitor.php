<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\SiteApi\FieldValue;

use Ibexa\Core\FieldType\Image\Value as ImageValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\Image\Value>
 */
final class ImageFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof ImageValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->id,
            'imageId' => $value->imageId,
            'mime' => $value->mime,
            'additionalData' => $value->additionalData,
            'alternativeText' => $value->alternativeText,
            'fileName' => $value->fileName,
            'fileSize' => $value->fileSize,
            'uri' => $value->uri,
            'inputUri' => $value->inputUri,
            'width' => $value->width,
            'height' => $value->height,
        ];
    }
}
