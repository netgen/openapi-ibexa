<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\IbexaFieldValue;

use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Netgen\TagsBundle\Core\FieldType\Tags\Value as TagsValue;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\TagsBundle\Core\FieldType\Tags\Value>
 */
final class TagsFieldValueVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof TagsValue;
    }

    /**
     * @return iterable<int, iterable<string, mixed>>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [...$this->visitTags($value)];
    }

    /**
     * @return iterable<int, array<string, mixed>>
     */
    private function visitTags(TagsValue $tagsValue): iterable
    {
        foreach ($tagsValue->tags as $tag) {
            yield [
                'id' => $tag->id,
                'remoteId' => $tag->remoteId,
                'parentTagId' => $tag->parentTagId,
                'keyword' => $tag->keyword,
            ];
        }
    }
}
