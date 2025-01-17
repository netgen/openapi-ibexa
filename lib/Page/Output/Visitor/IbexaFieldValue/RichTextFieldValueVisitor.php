<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Contracts\FieldTypeRichText\RichText\Converter;
use Ibexa\FieldTypeRichText\FieldType\RichText\Value as RichTextValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Ibexa\FieldTypeRichText\FieldType\RichText\Value>
 */
final class RichTextFieldValueVisitor implements VisitorInterface
{
    public function __construct(
        private Converter $richTextConverter,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof RichTextValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): string
    {
        return (string) $this->richTextConverter->convert($value->xml)->saveHTML();
    }
}
