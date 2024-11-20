<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Core\FieldType\Time\Value as TimeValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\Time\Value>
 */
final class TimeFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof TimeValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'time' => $value->time,
        ];
    }
}
