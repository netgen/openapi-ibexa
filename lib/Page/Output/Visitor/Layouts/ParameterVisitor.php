<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts;

use Netgen\Layouts\Exception\Parameters\ParameterException;
use Netgen\Layouts\Parameters\Parameter;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use RuntimeException;

use function is_object;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\Parameters\Parameter>
 */
final class ParameterVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Parameter;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $parameterDefinition = $value->getParameterDefinition();
        $parameterType = $parameterDefinition->getType();

        try {
            $parameterValue = $value->getValueObject() ?? $parameterType->toHash($parameterDefinition, $value->getValue());
        } catch (ParameterException) {
            $parameterValue = $parameterType->toHash($parameterDefinition, $value->getValue());
        }

        if (is_object($parameterValue)) {
            try {
                $parameterValue = $outputVisitor->visit($parameterValue);
            } catch (RuntimeException) {
                $parameterValue = null;
            }
        }

        return [
            'parameterType' => $parameterType::getIdentifier(),
            'isEmpty' => $value->isEmpty(),
            'value' => $parameterValue,
        ];
    }
}
