<?php

if (! function_exists('module_path')) {
    /**
     * Get the path to the modules folder.
     */
    function modules_path(string $path = ''): string
    {
        return base_path('modules'.($path ? '/'.$path : ''));
    }
}
