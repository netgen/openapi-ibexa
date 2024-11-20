<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Core\FieldType\TextBlock\Value as TextBlockValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\TextBlock\Value>
 */
final class TextBlockFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof TextBlockValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'text' => $value->text,
        ];
    }
}
