<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\Page;

use Netgen\IbexaSiteApi\API\Values\Content;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\Layouts\API\Values\Layout\Layout;

final class Page
{
    /**
     * @var array<string, mixed>
     */
    private array $pageParts = [];

    public function __construct(
        private ?Content $content = null,
        private ?Layout $layout = null,
    ) {}

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function getLocation(): ?Location
    {
        return $this->content?->mainLocation;
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
