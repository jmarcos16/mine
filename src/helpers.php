<?php

/**
 * Get the base path of the project
 * 
 * @param string $path
 */
if(!function_exists('base_path')){
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/../' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}