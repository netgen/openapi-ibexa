<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts;

use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Block\Block;
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

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'blocks' => [...$this->visitBlocks($parameters['block'], $value, $outputVisitor)],
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    private function visitBlocks(Block $block, Placeholder $placeholder, OutputVisitor $outputVisitor): iterable
    {
        foreach ($this->blockService->loadPlaceholderBlocks($block, $placeholder->getIdentifier()) as $placeholderBlock) {
            try {
                yield $outputVisitor->visit($placeholderBlock);
            } catch (RuntimeException) {
                // Do nothing
            }
        }
    }
}
