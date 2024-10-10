<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Value as ContentTypeListValue;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Value>
 */
final class ContentTypeListFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof ContentTypeListValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'identifiers' => $value->identifiers,
        ];
    }
}
