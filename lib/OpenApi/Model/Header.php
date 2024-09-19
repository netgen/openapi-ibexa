<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Header
{
    public function __construct(
        private Schema $schema,
        private ParameterStyle $style = ParameterStyle::Simple,
        private bool $required = false,
    ) {}

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    public function getStyle(): ParameterStyle
    {
        return $this->style;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }
}
