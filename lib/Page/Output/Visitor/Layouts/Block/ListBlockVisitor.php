<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\Block;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Collection\Result\Pagerfanta\PagerFactory;
use Netgen\Layouts\Collection\Result\ResultSet;
use Netgen\OpenApiIbexa\Page\Output\OutputVisitor;
use Netgen\OpenApiIbexa\Page\Output\Visitor\Layouts\BlockVisitor;
use Netgen\OpenApiIbexa\Page\Output\VisitorInterface;

/**
 * @implements \Netgen\OpenApiIbexa\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class ListBlockVisitor extends BlockVisitor implements VisitorInterface
{
    public function __construct(
        private PagerFactory $pagerFactory,
    ) {}

    public function accept(object $value): bool
    {
        return $value instanceof Block && $value->getDefinition()->getIdentifier() === 'list';
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        /** @var \Netgen\Layouts\Collection\Result\ResultSet $resultSet */
        $resultSet = $this->pagerFactory
            ->getPager($value->getCollection('default'), 1)
            ->getCurrentPageResults();

        return [
            'columns' => (int) ($value->getParameter('number_of_columns')->getValue() ?? 1),
            'items' => [...$this->visitItems($resultSet, $outputVisitor)],
        ] + $this->visitBasicProperties($value);
    }

    /**
     * @return iterable<int, array<array-key, mixed>>
     */
    private function visitItems(ResultSet $resultSet, OutputVisitor $outputVisitor): iterable
    {
        foreach ($resultSet->getResults() as $result) {
            $valueObject = $result->getItem()->getObject();

            if ($valueObject instanceof Location) {
                yield $outputVisitor->visit($valueObject->content);
            } elseif ($valueObject instanceof Content) {
                yield $outputVisitor->visit($valueObject);
            }
        }
    }
}
