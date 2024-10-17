<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\Layouts;

use DateTimeInterface;
use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\Layouts\API\Values\Layout\Layout;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\Layouts\API\Values\Layout\Layout>
 */
final class LayoutVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Layout;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->getId()->toString(),
            'layoutType' => $value->getLayoutType()->getIdentifier(),
            'name' => $value->getName(),
            'description' => $value->getDescription(),
            'creationDate' => $value->getCreated()->format(DateTimeInterface::ATOM),
            'modificationDate' => $value->getModified()->format(DateTimeInterface::ATOM),
            'isShared' => $value->isShared(),
            'mainLocale' => $value->getMainLocale(),
            'availableLocales' => $value->getAvailableLocales(),
            'zones' => (static function (Layout $layout) use ($outputVisitor) {
                foreach ($layout->getZones() as $zone) {
                    yield $zone->getIdentifier() => $outputVisitor->visit($zone);
                }
            })($value),
        ];
    }
}
