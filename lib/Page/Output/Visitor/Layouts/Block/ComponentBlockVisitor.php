<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\Block;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Ibexa\Block\BlockDefinition\Handler\ComponentHandler;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\BlockVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class ComponentBlockVisitor extends BlockVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getHandler() instanceof ComponentHandler;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $valueObject = $value->getParameter('content')->getValueObject();

        return [
            'type' => 'component',
            'componentType' => $value->getDefinition()->getIdentifier(),
            'content' => $valueObject !== null ? $outputVisitor->visit($valueObject) : null,
        ] + $this->visitBasicProperties($value);
    }
}
