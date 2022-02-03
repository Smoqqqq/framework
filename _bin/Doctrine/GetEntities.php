<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$GLOBALS["entities"] = array();

function getEntities($folder = null)
{
    global $env;
    if ($folder === null) $folder = $env['ENTITIES'];
    $foldersToRead = array();
    $files = array_diff(scandir($folder), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$folder/$file";
        if (is_dir($filePath)) {
            array_push($foldersToRead, $filePath);
        } else {
            $content = file_get_contents($filePath);
            $class = explode("class ", $content)[1];
            $class = explode(" ", $class)[0];
            $entity = array(
                "filepath" => $filePath,
                "class" => $class
            );
            array_push($GLOBALS["entities"], $entity);
        }
    }
    foreach ($foldersToRead as $folder) {
        getEntities($folder);
    }
    return $GLOBALS["entities"];
}

function getEntityManager()
{
    global $env;
    $path = array($env["ENTITIES"]);

    $entities = getEntities();

    foreach($entities as $entity){
        require_once($entity["filepath"]);
    }

    $isDevMode = false;

    // the connection configuration
    $dbParams = array(
        'driver'   => $env["DB_DRIVER"],
        'user'     => $env["DB_USER"],
        'password' => $env["DB_PASS"],
        'dbname'   => $env["DB_NAME"],
    );

    $config = Setup::createAnnotationMetadataConfiguration($path, $isDevMode);
    $entityManager = EntityManager::create($dbParams, $config);

    return $entityManager;
}