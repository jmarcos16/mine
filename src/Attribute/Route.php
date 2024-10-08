<?php

namespace Jmarcos16\MiniRouter\Attribute;

use Attribute;

#[Attribute]
final readonly class Route
{
    /**
     * @param string $uri
     * @param array<string>|string $methods
     * @param string $name
     */
    public function __construct(
        protected string $uri,
        protected array|string $methods = ['GET'],
        protected string $name = ''
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
