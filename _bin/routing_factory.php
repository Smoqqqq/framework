<?php

$templatePath = "templates";

if (isset($env["TEMPLATES_FOLDER"])) {
    if (is_dir($env["TEMPLATES_FOLDER"])) {
        $templatePath = $env["TEMPLATES_FOLDER"];
    } else {
        throw new Exception("The provided TEMPLATES_FOLDER is not a valid folder");
    }
}

$files = array_diff(scandir($templatePath), array('.', '..'));
$routes = array();

readFolder($templatePath);

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

$slug = $_SERVER["REQUEST_URI"];

$dir = explode("\\", getcwd());
$dir = $dir[count($dir) - 1];

$slug = str_replace("/$dir/", "", $slug);

$title = "";
$description = "";
$page = "";

function showPage()
{
    global $templatePath;
    global $slug;
    global $files;
    if (file_exists("$templatePath/$slug.php")) {
        $page = "$templatePath/$slug.php";
    } else {
        foreach ($files as $file) {
            $page = scanFileForUrl($file);
        }
        if(!$page) $page = "$templatePath/404.php";
    }
    getMeta($page);
}

function scanFileForUrl($file)
{
    global $templatePath;
    global $slug;
    if (!is_dir("$templatePath/$file")) {
        $file = "$templatePath/$file";
        $content = file_get_contents($file);
        $content = explode("###", $content)[0];
        if (strpos($content, "url:")) {
            $url = explode(";", explode("url:", $content)[1])[0];
            $url = str_replace([" ", "\r", "\n"], "", $url);
            if ($url === $slug) {
                return $file;
            }
            return false;
        }
        return false;
    } else {
        $files = array_diff(scandir("$templatePath/$file"), array('.', '..'));
        foreach ($files as $ffile) {
            $page = scanFileForUrl("$file/$ffile");
            if($page) return $page;
        }
    }
}

function getMeta($file)
{
    global $title;
    global $description;

    $index = "view.php";

    $content = file_get_contents($file);

    if (!strpos($content, "###")) {
        throw new ErrorException("Can't find meta block in page $file");
    }

    $metaBlock = explode("###", $content)[0];

    if (strpos($metaBlock, "title:") != 0) {
        throw new ErrorException("Can't find title block in page $file");
    }
    if (!strpos($metaBlock, "description:")) {
        throw new ErrorException("Can't find description block in page $file");
    }

    $title = explode(";", explode("title:", $metaBlock)[1])[0];
    $description = explode(";", explode("description:", $metaBlock)[1])[0];
    $newContent = explode("###", $content)[1];

    file_put_contents($index, $newContent);
}

function getRoute($path)
{
    $base = $_SERVER["HTTP_HOST"] . str_replace("/index.php", "", $_SERVER["SCRIPT_NAME"]);
    return "$base/$path";
}
