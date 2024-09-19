<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

final class Parameter
{
    private ParameterStyle $style;

    public function __construct(
        private string $name,
        private ParameterIn $in,
        private Schema $schema,
        ?ParameterStyle $style = null,
        private bool $required = false,
    ) {
        $this->style = $style ?? match ($in) {
            ParameterIn::Query, ParameterIn::Cookie => ParameterStyle::Form,
            ParameterIn::Header, ParameterIn::Path => ParameterStyle::Simple,
        };
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIn(): ParameterIn
    {
        return $this->in;
    }

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
        if ($this->in === ParameterIn::Path) {
            return true;
        }

        return $this->required;
    }
}
