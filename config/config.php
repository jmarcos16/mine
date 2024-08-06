<?php

use Jmarcos16\Mine\Tests\Controllers\TestController;

return [
    'controllers' => [
        TestController::class
    ],
    'bindings' => [
        'test' => 'Jmarcos16\Mine\Tests\Controllers\TestController'
    ]
];