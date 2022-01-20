<?php

/**
 * dumps and die
 * 
 * @param mixed $value      The thing to dump
 */
function dd($value){
    var_dump($value);
    die;
}

/**
 * links an asset
 * 
 * @param String $asset     The asset to link
 * 
 * @return String           The relative path of the asset for the current page
 */
function asset($asset)
{
    global $slug;
    $offset = "";
    $array = explode("/", $slug);
    $i = 0;
    foreach ($array as $tab) {
        if ($i > 0) $offset .= "../";
        $i++;
    }
    return $offset . $asset;
}

/**
 * get the absolute route for a page
 * 
 * @param String $route     The page to link
 * 
 * @return String           Absolute path of the page 
 */
function getRoute($route)
{
    global $base;
    global $routes;
    return $base . $routes[$route];
}