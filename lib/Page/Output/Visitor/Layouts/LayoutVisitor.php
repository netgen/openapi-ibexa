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
            'type' => $value->getLayoutType()->getIdentifier(),
            'name' => $value->getName(),
            'description' => $value->getDescription(),
            'creation_date' => $value->getCreated()->format(DateTimeInterface::ATOM),
            'modification_date' => $value->getModified()->format(DateTimeInterface::ATOM),
            'is_shared' => $value->isShared(),
            'main_locale' => $value->getMainLocale(),
            'available_locales' => $value->getAvailableLocales(),
            'zones' => (static function (Layout $layout) use ($outputVisitor) {
                foreach ($layout->getZones() as $zone) {
                    yield $zone->getIdentifier() => $outputVisitor->visit($zone);
                }
            })($value),
        ];
    }
}
