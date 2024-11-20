<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Netgen\IbexaFieldTypeEnhancedLink\FieldType\Value as EnhancedLinkValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\IbexaFieldTypeEnhancedLink\FieldType\Value>
 */
final class EnhancedLinkFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof EnhancedLinkValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'target' => $value->target,
            'label' => $value->label,
            'reference' => $value->reference,
            'suffix' => $value->suffix,
        ];
    }
}
