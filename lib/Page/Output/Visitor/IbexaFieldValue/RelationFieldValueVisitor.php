<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Core\FieldType\Relation\Value as RelationValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\Relation\Value>
 */
final class RelationFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof RelationValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'destinationContentId' => $value->destinationContentId,
        ];
    }
}
