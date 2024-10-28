<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts\Block;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Ibexa\Block\BlockDefinition\Handler\ComponentHandler;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class ComponentBlockVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getHandler() instanceof ComponentHandler;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $valueObject = $value->getParameter('content')->getValueObject();

        return [
            'id' => $value->getId()->toString(),
            'type' => 'component',
            'componentType' => $value->getDefinition()->getIdentifier(),
            'content' => $valueObject !== null ? $outputVisitor->visit($valueObject) : null,
        ];
    }
}
