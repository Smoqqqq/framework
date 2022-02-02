<?php

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
            $controller = array(
                "filepath" => $filePath,
                "class" => $class
            );
            array_push($GLOBALS["entities"], $controller);
        }
    }
    foreach ($foldersToRead as $folder) {
        getEntities($folder);
    }
    return $GLOBALS["entities"];
}