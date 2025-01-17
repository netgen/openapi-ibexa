<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use DateTimeInterface;
use Ibexa\Core\FieldType\DateAndTime\Value as DateAndTimeValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\DateAndTime\Value>
 */
final class DateAndTimeFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof DateAndTimeValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): ?string
    {
        return $value->value?->format(DateTimeInterface::ATOM);
    }
}
