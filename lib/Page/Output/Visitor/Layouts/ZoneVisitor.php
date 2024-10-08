<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\Layouts\API\Service\BlockService;
use Netgen\Layouts\API\Values\Layout\Zone;
use RuntimeException;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Layout\Zone>
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

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'blocks' => (function (Zone $zone) use ($outputVisitor) {
                foreach ($this->blockService->loadZoneBlocks($zone) as $block) {
                    try {
                        yield $outputVisitor->visit($block);
                    } catch (RuntimeException) {
                        // Do nothing
                    }
                }
            })($value->getLinkedZone() ?? $value),
        ];
    }
}
