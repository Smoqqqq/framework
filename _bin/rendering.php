<?php

function readFolder($folder)
{
    global $routes;
    $foldersToRead = array();
    $files = array_diff(scandir($folder), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$folder/$file";
        if (is_dir($filePath)) {
            array_push($foldersToRead, $folder . "/" . $file);
        } else {
            $filePath = str_replace("templates/", "", $filePath);
            array_push($routes, $filePath);
        }
    }
    foreach ($foldersToRead as $folder) {
        readFolder($folder);
    }
}