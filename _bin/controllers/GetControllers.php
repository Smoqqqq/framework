<?php

$GLOBALS["controllers"] = array();

function getControllers($folder = null)
{
    global $env;
    if ($folder === null) $folder = $env['CONTROLLERS'];
    $foldersToRead = array();
    $files = array_diff(scandir($folder), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$folder/$file";
        if (is_dir($filePath)) {
            array_push($foldersToRead, $filePath);
        } else {
            $controllerContent = file_get_contents($filePath);
            $class = explode("class ", $controllerContent)[1];
            $class = explode(" ", $class)[0];
            $controller = array(
                "filepath" => $filePath,
                "class" => $class
            );
            array_push($GLOBALS["controllers"], $controller);
        }
    }
    foreach ($foldersToRead as $folder) {
        getControllers($folder);
    }
    return $GLOBALS["controllers"];
}