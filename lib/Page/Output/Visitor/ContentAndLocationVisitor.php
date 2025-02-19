<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

use Netgen\OpenApiIbexa\Page\ContentAndLocation;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\OpenApiIbexa\Page\ContentAndLocation>
 */
final class ContentAndLocationVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof ContentAndLocation;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        yield from [
            'content' => $outputVisitor->visit($value->getContent(), $parameters),
            'location' => $outputVisitor->visit($value->getLocation(), $parameters),
        ];
    }
}
