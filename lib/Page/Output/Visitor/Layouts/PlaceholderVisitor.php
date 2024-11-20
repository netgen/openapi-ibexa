<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts;

use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Block\Placeholder;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use RuntimeException;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Placeholder>
 */
final class PlaceholderVisitor implements VisitorInterface
{
    public function __construct(
        private BlockService $blockService,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Placeholder;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'blocks' => (function (Placeholder $placeholder) use ($outputVisitor, $parameters) {
                foreach ($this->blockService->loadPlaceholderBlocks($parameters['block'], $placeholder->getIdentifier()) as $block) {
                    try {
                        yield $outputVisitor->visit($block);
                    } catch (RuntimeException) {
                        // Do nothing
                    }
                }
            })($value),
        ];
    }
}
