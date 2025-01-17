<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts;

use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Layout\Zone;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use RuntimeException;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Layout\Zone>
 */
final class ZoneVisitor implements VisitorInterface
{
    public function __construct(
        private BlockService $blockService,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Zone;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'blocks' => [...$this->visitBlocks($value->getLinkedZone() ?? $value, $outputVisitor)],
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    private function visitBlocks(Zone $zone, OutputVisitor $outputVisitor): iterable
    {
        foreach ($this->blockService->loadZoneBlocks($zone) as $block) {
            try {
                yield $outputVisitor->visit($block);
            } catch (RuntimeException) {
                // Do nothing
            }
        }
    }
}
