<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\SiteApi\FieldValue;

use Ibexa\Core\FieldType\TextLine\Value as TextLineValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\TextLine\Value>
 */
final class TextLineFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof TextLineValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'text' => $value->text,
        ];
    }
}
