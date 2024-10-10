<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Novactive\Bundle\eZSEOBundle\Core\FieldType\Metas\Value as MetasValue;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Novactive\Bundle\eZSEOBundle\Core\FieldType\Metas\Value>
 */
final class MetasFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof MetasValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'metas' => (static function (MetasValue $value) {
                foreach ($value->metas as $meta) {
                    yield [
                        'name' => $meta->getName(),
                        'content' => $meta->getContent(),
                        'fieldType' => $meta->getFieldType(),
                        'required' => $meta->getRequired(),
                        'minLength' => $meta->getMinLength(),
                        'maxLength' => $meta->getMaxLength(),
                    ];
                }
            })($value),
        ];
    }
}
