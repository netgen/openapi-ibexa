<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\IbexaFieldValue;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\TagsBundle\Core\FieldType\Tags\Value as TagsValue;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\TagsBundle\Core\FieldType\Tags\Value>
 */
final class TagsFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof TagsValue;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'tags' => (static function (TagsValue $value) {
                foreach ($value->tags as $tag) {
                    yield [
                        'id' => $tag->id,
                        'remoteId' => $tag->remoteId,
                        'parentTagId' => $tag->parentTagId,
                        'keyword' => $tag->keyword,
                    ];
                }
            })($value),
        ];
    }
}
