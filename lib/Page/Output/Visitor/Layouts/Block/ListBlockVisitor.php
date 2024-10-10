<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts\Block;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Collection\Result\Pagerfanta\PagerFactory;
use Netgen\Layouts\Collection\Result\ResultSet;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Block\Block>
 */
final class ListBlockVisitor implements VisitorInterface
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
            'id' => $value->getId()->toString(),
            'type' => $value->getDefinition()->getIdentifier(),
            'columns' => (int) ($value->getParameter('number_of_columns')->getValue() ?? 1),
            'items' => (static function (ResultSet $resultSet) use ($outputVisitor) {
                foreach ($resultSet->getResults() as $result) {
                    $valueObject = $result->getItem()->getObject();

                    if ($valueObject === null) {
                        continue;
                    }

                    yield $valueObject instanceof Location ?
                        $outputVisitor->visit($valueObject->content) :
                        $outputVisitor->visit($valueObject);
                }
            })($resultSet),
        ];
    }
}
