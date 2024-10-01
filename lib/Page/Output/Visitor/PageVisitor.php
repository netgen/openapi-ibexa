<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\IbexaOpenApi\Page\Page;

use function is_object;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\IbexaOpenApi\Page\Page>
 */
final class PageVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Page;
    }

    public function visit(object $value, OutputVisitor $outputVisitor): iterable
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
