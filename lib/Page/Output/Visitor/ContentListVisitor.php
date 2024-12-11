<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

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

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        foreach ($value->getContentItems() as $content) {
            yield [
                'content' => $outputVisitor->visit($content, $parameters),
                'location' => $content->mainLocation !== null ?
                    $outputVisitor->visit($content->mainLocation, $parameters) :
                    null,
            ];
        }
    }
}
