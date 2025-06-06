<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Novactive\Bundle\eZSEOBundle\Core\FieldType\Metas\Value as MetasValue;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Novactive\Bundle\eZSEOBundle\Core\FieldType\Metas\Value>
 */
final class MetasFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof MetasValue;
    }

    /**
     * @return iterable<int, iterable<string, mixed>>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [...$this->visitMetas($value)];
    }

    /**
     * @return iterable<int, array<string, mixed>>
     */
    private function visitMetas(MetasValue $metasValue): iterable
    {
        foreach ($metasValue->metas as $meta) {
            yield [
                'name' => $meta->getName(),
                'content' => $meta->getContent(),
                'fieldType' => $meta->getFieldType(),
                'required' => $meta->getRequired(),
                'minLength' => $meta->getMinLength(),
                'maxLength' => $meta->getMaxLength(),
            ];
        }
    }
}
