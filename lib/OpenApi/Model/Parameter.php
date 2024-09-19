<?php

declare(strict_types=1);

namespace Netgen\IbexaOpenApi\OpenApi\Model;

use InvalidArgumentException;

use function in_array;
use function sprintf;

final class Parameter
{
    private ParameterStyle $style;

    /**
     * @var array<string, \Netgen\IbexaOpenApi\OpenApi\Model\ParameterStyle[]>
     */
    private array $allowedStyles = [
        ParameterIn::Query->value => [ParameterStyle::Form, ParameterStyle::SpaceDelimited, ParameterStyle::PipeDelimited, ParameterStyle::deepObject],
        ParameterIn::Header->value => [ParameterStyle::Simple],
        ParameterIn::Path->value => [ParameterStyle::Matrix, ParameterStyle::Label, ParameterStyle::Simple],
        ParameterIn::Cookie->value => [ParameterStyle::Form],
    ];

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

        if (!in_array($this->style, $this->allowedStyles[$this->in->value], true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Style "%s" is not allowed for "%s" location of "%s" parameter',
                    $this->style->value,
                    $this->in->value,
                    $this->name,
                ),
            );
        }
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
