<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

final class LocationList
{
    /**
     * @param iterable<\Netgen\IbexaSiteApi\API\Values\Location> $locations
     */
    public function __construct(
        private iterable $locations,
    ) {}

    /**
     * @return iterable<\Netgen\IbexaSiteApi\API\Values\Location>
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }
}
