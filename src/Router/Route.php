<?php

namespace Jmarcos16\Mine\Router;

use Attribute;

#[Attribute]
final readonly class Route
{
    public function __construct(
        protected string $uri,
        protected string $endpoint,
    ){}

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
}