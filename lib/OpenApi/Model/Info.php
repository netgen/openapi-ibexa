<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Info
{
    private const API_VERSION = '1.0.0';

    private string $title = 'OpenAPI implementation for Ibexa CMS';

    private string $version = self::API_VERSION;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
