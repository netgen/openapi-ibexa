<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts\Block;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\Layouts\API\Values\Block\Block;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class TitleBlockVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getIdentifier() === 'title';
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->getId()->toString(),
            'type' => $value->getDefinition()->getIdentifier(),
            'tag' => $value->getParameter('tag')->getValue() ?? '',
            'title' => $value->getParameter('title')->getValue() ?? '',
        ];
    }
}
