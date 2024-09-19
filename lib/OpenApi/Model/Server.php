<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Server
{
    public function __construct(
        private string $url,
    ) {}

    public function getUrl(): string
    {
        return $this->url;
    }
}
