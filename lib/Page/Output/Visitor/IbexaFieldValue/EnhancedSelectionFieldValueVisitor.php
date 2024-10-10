<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Netgen\Bundle\EnhancedSelectionBundle\Core\FieldType\EnhancedSelection\Value as EnhancedSelectionValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Bundle\EnhancedSelectionBundle\Core\FieldType\EnhancedSelection\Value>
 */
final class EnhancedSelectionFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof EnhancedSelectionValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'identifiers' => $value->identifiers,
        ];
    }
}
