<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\FieldTypeMatrix\FieldType\Value as MatrixValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

use function array_map;
use function iterator_to_array;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Ibexa\FieldTypeMatrix\FieldType\Value>
 */
final class MatrixFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof MatrixValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): array
    {
        return array_map(
            static fn ($row): array => $row->getCells(),
            iterator_to_array($value->getRows()),
        );
    }
}
