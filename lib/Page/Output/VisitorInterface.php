<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output;

/**
 * Visits values into hash representation.
 *
 * Hash format is either a scalar value, a hash array (associative array),
 * a pure numeric array or a nested combination of these.
 *
 * @template T of object
 */
interface VisitorInterface
{
    /**
     * Check if the visitor accepts the given $value.
     */
    public function accept(object $value): bool;

    /**
     * Visit the given $value into hash array or scalar representation.
     *
     * @param T $value
     * @param array<string, mixed> $parameters
     *
     * @return iterable<array-key, mixed>|int|string|bool|float|null
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable|int|string|bool|float|null;
}
