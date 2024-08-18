<?php

/**
 * Get the base path of the project
 *
 * @param string $path
 */
if(!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/../' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

/**
 * Dump and die
 *
 * @param mixed ...$params
 */
if(!function_exists('dd')) {
    function dd(...$params): void //@phpstan-ignore-line
    {

        $trace = debug_backtrace();
        $file  = $trace[0]['file'];
        $line  = $trace[0]['line'];

        echo "File: $file" . PHP_EOL;
        echo "Line: $line" . PHP_EOL;
        var_dump(...$params);

        die();
    }
}
