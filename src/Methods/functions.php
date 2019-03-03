<?php

use Archie\View\DSTwig;
$GLOBALS['__config'] = [];

if (!function_exists('view')) {
    function view($template, $data = [])
    {
        $twig = new DSTwig();
        return $twig->dispatch($template, $data);
    }
}

if (!function_exists('config')) {
    function config($key = null)
    {
        $GLOBALS['__config'] = ($GLOBALS['__config']) ? $GLOBALS['__config'] : require_once BASE_FOLDER . 'config/config.php';
        return $GLOBALS['__config'][$key] ?? $GLOBALS['__config'];
    }
}
