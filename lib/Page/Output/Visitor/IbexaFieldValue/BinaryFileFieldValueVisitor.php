<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Core\FieldType\BinaryFile\Value as BinaryFileValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\BinaryFile\Value>
 */
final class BinaryFileFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof BinaryFileValue;
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
            'downloadCount' => $value->downloadCount,
        ];
    }
}
