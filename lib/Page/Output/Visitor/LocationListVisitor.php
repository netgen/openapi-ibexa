<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

use Netgen\OpenApiIbexa\Page\ContentAndLocation;
use Netgen\OpenApiIbexa\Page\LocationList;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\OpenApiIbexa\Page\LocationList>
 */
final class LocationListVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof LocationList;
    }

    /**
     * @return iterable<int, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        foreach ($value->getLocations() as $location) {
            yield $outputVisitor->visit(new ContentAndLocation($location->content, $location), $parameters);
        }
    }
}
