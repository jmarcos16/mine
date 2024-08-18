<?php

namespace Jmarcos16\MiniRouter\Contracts;

use Jmarcos16\MiniRouter\Request;

interface Router
{
    public function handle(?Request $request = null): void;
}