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
 * dumps
 * 
 * @param mixed $value      The thing to dump
 */
function d($value){
    var_dump($value);
}

function asset($asset)
{
    global $env;
    $slug = str_replace(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']),'',$_SERVER['REQUEST_URI']);
    $assetFolder = (isset($env["ASSETS_FOLDER"])) ? $env["ASSETS_FOLDER"] : "assets";
    $offset = "";
    $array = explode("/", $slug);
    $i = 0;
    foreach ($array as $tab) {
        if ($i > 0) $offset .= "../";
        $i++;
    }
    return $offset . $assetFolder . "/" . $asset;
}