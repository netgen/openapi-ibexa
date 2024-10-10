<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\SiteApi\FieldValue;

use DateTimeInterface;
use Ibexa\Core\FieldType\DateAndTime\Value as DateAndTimeValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\DateAndTime\Value>
 */
final class DateAndTimeFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof DateAndTimeValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'value' => $value->value?->format(DateTimeInterface::ATOM),
        ];
    }
}
