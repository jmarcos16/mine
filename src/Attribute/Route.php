<?php

namespace Jmarcos16\Mine\Attribute;

use Attribute;

#[Attribute]
final readonly class Route
{
    public function __construct(
        protected string $uri,
        protected ?array $methods = ['GET'],
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethods(): ?array
    {
        return $this->methods;
    }
}
