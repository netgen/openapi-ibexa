<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\Block;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\ContainerDefinitionHandlerInterface;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\BlockVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class PlaceholderBlockVisitor extends BlockVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getHandler() instanceof ContainerDefinitionHandlerInterface;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'type' => 'placeholder',
            'placeholderType' => $value->getDefinition()->getIdentifier(),
            'placeholders' => (static function (Block $block) use ($outputVisitor) {
                foreach ($block->getPlaceholders() as $placeholder) {
                    yield $placeholder->getIdentifier() => $outputVisitor->visit($placeholder, ['block' => $block]);
                }
            })($value),
        ] + $this->visitBasicProperties($value);
    }
}
