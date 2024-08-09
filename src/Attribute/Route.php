<?php

namespace Jmarcos16\RouterMine\Attribute;

use Attribute;

#[Attribute]
final readonly class Route
{
    /**
     * @param string $uri
     * @param array<string>|string $methods
     */
    public function __construct(
        protected string $uri,
        protected array|string $methods = ['GET'],
    ) {
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array<string>
     */
    public function getMethods(): array
    {
        return is_array($this->methods) ? $this->methods : [$this->methods];
    }
}
