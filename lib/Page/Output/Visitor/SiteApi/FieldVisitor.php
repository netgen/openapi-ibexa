<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\SiteApi;

use Netgen\IbexaSiteApi\API\Values\Field;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use RuntimeException;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaSiteApi\API\Values\Field>
 */
final class FieldVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Field;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        try {
            $fieldValue = $outputVisitor->visit($value->value);
        } catch (RuntimeException) {
            $fieldValue = null;
        }

        return [
            'fieldType' => $value->fieldTypeIdentifier,
            'isEmpty' => $value->isEmpty(),
            'value' => $fieldValue,
        ];
    }
}
