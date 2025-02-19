<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts;

use Generator;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\Handler\DynamicParameter;
use Netgen\Layouts\Block\ContainerDefinitionInterface;
use Netgen\Layouts\Collection\Result\Pagerfanta\PagerFactory;
use Netgen\Layouts\Collection\Result\ResultSet;
use Netgen\OpenApiIbexa\Page\ContentAndLocation;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;
use ReflectionClass;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class BlockVisitor implements VisitorInterface
{
    public function __construct(
        private PagerFactory $pagerFactory,
        private ConfigResolverInterface $configResolver,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Block;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        $properties = [
            'id' => $value->getId()->toString(),
            'definitionIdentifier' => $value->getDefinition()->getIdentifier(),
            'viewType' => $value->getViewType(),
            'itemViewType' => $value->getItemViewType(),
            'parameters' => [...$this->visitParameters($value, $outputVisitor)],
            'dynamicParameters' => [...$this->visitDynamicParameters($value, $outputVisitor)],
        ];

        if ($value->hasCollection('default')) {
            /** @var \Netgen\Layouts\Collection\Result\ResultSet $resultSet */
            $resultSet = $this->pagerFactory
                ->getPager($value->getCollection('default'), 1)
                ->getCurrentPageResults();

            $properties['items'] = [...$this->visitItems($resultSet, $outputVisitor)];
        }

        if ($value->getDefinition() instanceof ContainerDefinitionInterface) {
            $properties['placeholders'] = [...$this->visitPlaceholders($value, $outputVisitor)];
        }

        return $properties;
    }

    /**
     * @return iterable<string, mixed>
     */
    private function visitParameters(Block $block, OutputVisitor $outputVisitor): iterable
    {
        foreach ($block->getParameters() as $identifier => $parameter) {
            yield $identifier => $outputVisitor->visit($parameter);
        }
    }

    /**
     * @return iterable<string, mixed>
     */
    private function visitDynamicParameters(Block $block, OutputVisitor $outputVisitor): iterable
    {
        foreach ($this->getDynamicParametersList($block) as $identifier) {
            $parameter = $block->getDynamicParameter($identifier);

            yield $identifier => $outputVisitor->visit($parameter);
        }
    }

    /**
     * @return \Generator<string>
     */
    private function getDynamicParametersList(Block $block): Generator
    {
        $schemaNames = $this->configResolver->getParameter(
            'layouts_dynamic_parameters_schema',
            'netgen_open_api_ibexa',
        )[$block->getDefinition()->getIdentifier()] ?? [];

        $reflectionClass = new ReflectionClass($block->getDefinition()->getHandler()::class);
        $reflectionAttributes = $reflectionClass->getAttributes(DynamicParameter::class);

        foreach ($reflectionAttributes as $reflectionAttribute) {
            /** @var \Netgen\Layouts\Block\BlockDefinition\Handler\DynamicParameter $attribute */
            $attribute = $reflectionAttribute->newInstance();

            if (isset($schemaNames[$attribute->parameterName]['reference_name'])) {
                yield $attribute->parameterName;
            }
        }

        foreach ($block->getDefinition()->getPlugins() as $plugin) {
            $reflectionClass = new ReflectionClass($plugin::class);
            $reflectionAttributes = $reflectionClass->getAttributes(DynamicParameter::class);

            foreach ($reflectionAttributes as $reflectionAttribute) {
                /** @var \Netgen\Layouts\Block\BlockDefinition\Handler\DynamicParameter $attribute */
                $attribute = $reflectionAttribute->newInstance();

                if (isset($schemaNames[$attribute->parameterName]['reference_name'])) {
                    yield $attribute->parameterName;
                }
            }
        }
    }

    /**
     * @return iterable<int, mixed>
     */
    private function visitItems(ResultSet $resultSet, OutputVisitor $outputVisitor): iterable
    {
        foreach ($resultSet->getResults() as $result) {
            $valueObject = $result->getItem()->getObject();

            if ($valueObject instanceof Location) {
                yield $outputVisitor->visit(new ContentAndLocation($valueObject->content, $valueObject));
            } elseif ($valueObject instanceof Content) {
                yield $outputVisitor->visit(new ContentAndLocation($valueObject, $valueObject->mainLocation));
            }
        }
    }

    /**
     * @return iterable<string, mixed>
     */
    private function visitPlaceholders(Block $block, OutputVisitor $outputVisitor): iterable
    {
        foreach ($block->getPlaceholders() as $placeholder) {
            yield $placeholder->getIdentifier() => $outputVisitor->visit($placeholder, ['block' => $block]);
        }
    }
}
