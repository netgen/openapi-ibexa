<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page\Output\Visitor\SiteApi;

use Netgen\IbexaOpenApi\Page\Output\OutputVisitor;
use Netgen\IbexaOpenApi\Page\Output\VisitorInterface;
use Netgen\IbexaSiteApi\API\Values\Location;

use function array_map;

/**
 * @implements \Netgen\IbexaOpenApi\Page\Output\VisitorInterface<\Netgen\IbexaSiteApi\API\Values\Location>
 */
final class LocationVisitor implements VisitorInterface
{
    public function accept(object $value): bool
    {
        return $value instanceof Location;
    }

    public function visit(object $value, OutputVisitor $outputVisitor, array $parameters = []): iterable
    {
        return [
            'id' => $value->id,
            'type' => 'location',
            'remoteId' => $value->remoteId,
            'contentId' => $value->contentId,
            'parentLocationId' => $value->parentLocationId,
            'pathString' => $value->pathString,
            'pathArray' => array_map('intval', $value->pathArray),
            'depth' => $value->depth,
        ];
    }
}
