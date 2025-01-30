<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

use Netgen\OpenApiIbexa\Page\ContentAndLocation;
use Netgen\OpenApiIbexa\Page\ContentList;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\OpenApiIbexa\Page\ContentList>
 */
final class ContentListVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof ContentList;
    }

    /**
     * @return iterable<int, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        foreach ($value->getContentItems() as $content) {
            yield $outputVisitor->visit(new ContentAndLocation($content, $content->mainLocation), $parameters);
        }
    }
}
