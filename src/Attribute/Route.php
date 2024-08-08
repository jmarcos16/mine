<?php

namespace Jmarcos16\RouterMine\Attribute;

use Attribute;

#[Attribute]
final readonly class Route
{
    public function __construct(
        protected string $uri,
        protected array|string $methods = ['GET'],
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethods(): array
    {
        return is_array($this->methods) ? $this->methods : [$this->methods];
    }
}
