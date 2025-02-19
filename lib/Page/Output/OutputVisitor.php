<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output;

use RuntimeException;

use function get_debug_type;
use function is_iterable;
use function is_object;
use function is_scalar;
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
     * @return iterable<array-key, mixed>|int|string|bool|float|null
     */
    public function visit(mixed $value, array $parameters = []): iterable|int|string|bool|float|null
    {
        if (is_scalar($value)) {
            return $value;
        }

        if (is_object($value)) {
            return $this->visitObject($value, $parameters);
        }

        if (is_iterable($value)) {
            return [...$this->visitIterable($value, $parameters)];
        }

        return null;
    }

    /**
     * Visit the given object into hash array representation.
     *
     * @param array<string, mixed> $parameters
     *
     * @throws \RuntimeException if no sub-visitor is available for provided value
     *
     * @return iterable<array-key, mixed>|int|string|bool|float|null
     */
    private function visitObject(object $value, array $parameters = []): iterable|int|string|bool|float|null
    {
        foreach ($this->subVisitors as $subVisitor) {
            if ($subVisitor->accept($value)) {
                $visitedData = $subVisitor->visit($value, $this, $parameters);

                return is_iterable($visitedData) ? [...$visitedData] : $visitedData;
            }
        }

        throw new RuntimeException(
            sprintf("No visitor available for value of type '%s'", get_debug_type($value)),
        );
    }

    /**
     * Visit the given iterable into hash array representation.
     *
     * @param iterable<array-key, mixed> $value
     * @param array<string, mixed> $parameters
     *
     * @return iterable<array-key, mixed>
     */
    private function visitIterable(iterable $value, array $parameters = []): iterable
    {
        foreach ($value as $key => $item) {
            yield $key => $this->visit($item, $parameters);
        }
    }
}
