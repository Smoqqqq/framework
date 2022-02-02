<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * dumps and die
 * 
 * @param mixed $value      The thing to dump
 */
function dd($value){
    global $env;
    if($env["ENV"] === "prod") return false;
    var_dump($value);
    die;
}

/**
 * dumps
 * 
 * @param mixed $value      The thing to dump
 */
function d($value){
    global $env;
    if($env["ENV"] === "prod") return false;
    var_dump($value);
}

/**
 * Provides relative path for an asset
 */
// TODO: add twig asset() function
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

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function getEntityManager()
{
    global $env;
    $paths = array($env["ENTITIES"]);
    $isDevMode = false;

    // the connection configuration
    $dbParams = array(
        'driver'   => $env["DB_DRIVER"],
        'user'     => $env["DB_USER"],
        'password' => $env["DB_PASS"],
        'dbname'   => $env["DB_NAME"],
    );

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $entityManager = EntityManager::create($dbParams, $config);

    return $entityManager;
}