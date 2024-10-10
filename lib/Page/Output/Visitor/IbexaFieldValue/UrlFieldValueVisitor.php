<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Core\FieldType\Url\Value as UrlValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\Core\FieldType\Url\Value>
 */
final class UrlFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof UrlValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'link' => $value->link,
            'text' => $value->text,
        ];
    }
}
