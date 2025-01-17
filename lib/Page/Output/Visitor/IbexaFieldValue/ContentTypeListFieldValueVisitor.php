<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Value as ContentTypeListValue;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Bundle\ContentTypeListBundle\Core\FieldType\ContentTypeList\Value>
 */
final class ContentTypeListFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof ContentTypeListValue;
    }

    /**
     * @return iterable<int, string>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return $value->identifiers;
    }
}
