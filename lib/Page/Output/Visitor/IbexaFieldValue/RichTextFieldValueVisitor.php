<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Ibexa\Contracts\FieldTypeRichText\RichText\Converter;
use Ibexa\FieldTypeRichText\FieldType\RichText\Value as RichTextValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Ibexa\FieldTypeRichText\FieldType\RichText\Value>
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

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'xml' => (string) $value->xml->saveXML(),
            'html' => (string) $this->richTextConverter->convert($value->xml)->saveHTML(),
        ];
    }
}
