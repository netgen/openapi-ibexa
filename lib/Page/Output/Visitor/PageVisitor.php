<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor;

use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use Netgen\OpenApiIbexa\Page\Page;

use function is_object;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\OpenApiIbexa\Page\Page>
 */
final class PageVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Page;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        if ($value->getContent() !== null) {
            yield 'content' => $outputVisitor->visit($value->getContent());
        }

        if ($value->getLocation() !== null) {
            yield 'location' => $outputVisitor->visit($value->getLocation());
        }

        if ($value->getLayout() !== null) {
            yield 'layout' => $outputVisitor->visit($value->getLayout());
        }

        foreach ($value->getPageParts() as $identifier => $pagePart) {
            yield $identifier => is_object($pagePart) ?
                $outputVisitor->visit($pagePart) :
                $pagePart;
        }
    }
}
