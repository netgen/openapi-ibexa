<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output;

use RuntimeException;

use function get_debug_type;
use function sprintf;

final class OutputVisitor
{
    /**
     * @param iterable<\Netgen\OpenApiIbexa\Page\Output\VisitorInterface<object>> $subVisitors
     */
    public function __construct(
        private iterable $subVisitors,
    ) {}

    /**
     * Visit the given $value into hash array representation.
     *
     * @param array<string, mixed> $parameters
     *
     * @throws \RuntimeException if no sub-visitor is available for provided value
     *
     * @return array<string, mixed>
     */
    public function visit(object $value, array $parameters = []): array
    {
        foreach ($this->subVisitors as $subVisitor) {
            if ($subVisitor->accept($value)) {
                return [...$subVisitor->visit($value, $this, $parameters)];
            }
        }

        throw new RuntimeException(
            sprintf("No visitor available for value of type '%s'", get_debug_type($value)),
        );
    }
}
