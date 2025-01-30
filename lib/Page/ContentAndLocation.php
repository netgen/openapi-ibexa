<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;

final class ContentAndLocation
{
    public function __construct(
        private Content $content,
        private ?Location $location = null,
    ) {}

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }
}
