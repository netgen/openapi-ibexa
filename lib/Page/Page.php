<?php

declare(strict_types=1);

namespace Netgen\OpenApiIbexa\Page;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\API\Values\Layout\Layout;

final class Page
{
    /**
     * @var array<string, mixed>
     */
    private array $pageParts = [];

    /**
     * @param array<string, string> $queries
     */
    public function __construct(
        private ?Content $content = null,
        private ?Location $location = null,
        private array $queries = [],
        private ?Layout $layout = null,
    ) {}

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function getLocation(): ?Location
    {
        return $this->location ?? $this->content?->mainLocation;
    }

    /**
     * @return array<string, string>
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function addPagePart(string $identifier, mixed $pagePart): self
    {
        $this->pageParts[$identifier] = $pagePart;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPageParts(): array
    {
        return $this->pageParts;
    }
}
