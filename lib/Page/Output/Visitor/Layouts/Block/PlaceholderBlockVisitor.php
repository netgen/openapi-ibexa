<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts\Block;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\ContainerDefinitionHandlerInterface;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class PlaceholderBlockVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getHandler() instanceof ContainerDefinitionHandlerInterface;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->getId()->toString(),
            'type' => 'placeholder',
            'placeholderType' => $value->getDefinition()->getIdentifier(),
            'placeholders' => (static function (Block $block) use ($outputVisitor) {
                foreach ($block->getPlaceholders() as $placeholder) {
                    yield $placeholder->getIdentifier() => $outputVisitor->visit($placeholder, ['block' => $block]);
                }
            })($value),
        ];
    }
}
