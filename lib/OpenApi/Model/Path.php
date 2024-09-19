<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Path
{
    public function __construct(
        private Operation $get,
    ) {}

    public function getGet(): Operation
    {
        return $this->get;
    }
}
