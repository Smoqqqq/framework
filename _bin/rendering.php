<?php

$routes = array();

function readFolder($folder)
{
    $render = "var/cache/views";
    global $routes;
    $foldersToRead = array();
    $files = array_diff(scandir($folder), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$folder/$file";
        if (is_dir($filePath)) {
            array_push($foldersToRead, $folder . "/" . $file);
        } else {
            $url = getUrl($filePath);
            $renderPath = "$render/" . str_replace("/", "___", $filePath);;
            renderTemplateFile($filePath);
            $routes[$url] = array(
                "url" => $url,
                "source_file" => $filePath,
                "rendered_file" => $renderPath
            );
        }
    }
    foreach ($foldersToRead as $folder) {
        readFolder($folder);
    }
}

function getUrl($file){
    $content = file_get_contents($file);

    if (!strpos($content, "###")) {
        throw new ErrorException("Can't find meta block in page $file");
    }

    if(strpos($content, "url:") != false){
        return str_replace(" ", "", explode(".", explode(";", explode("url:", $content)[1])[0])[0]);
    }
    return explode(".", str_replace("templates/", "", $file))[0];
}

$templateFolder = "templates";

if (isset($env["TEMPLATES_FOLDER"])) {
    if (is_dir($env["TEMPLATES_FOLDER"])) {
        $templatePath = $env["TEMPLATES_FOLDER"];
    } else {
        throw new Exception("The provided TEMPLATES_FOLDER is not a valid folder");
    }
}

$slug = $_SERVER["REQUEST_URI"];

$dir = explode("\\", getcwd());
$dir = $dir[count($dir) - 1];

$slug = str_replace("/$dir/", "", $slug);

$page = "";

function render(){
    global $routes;
    global $slug;
    global $page;
    foreach($routes as $route){
        if($route["url"] === $slug){
            $page = str_replace("___", "/", $route["rendered_file"]);
        }
    }
    if($page === ""){
        $page = str_replace("___", "/", $routes["404"]["rendered_file"]);
    }
}

$layoutPath = "layouts";

if (isset($env["LAYOUTS_FOLDER"])) {
    if (is_dir($env["LAYOUTS_FOLDER"])) {
        $layoutPath = $env["LAYOUTS_FOLDER"];
    } else {
        throw new Exception("The provided LAYOUTS_FOLDER is not a valid folder");
    }
}    

$layout = file_get_contents($layoutPath . "/layout.html");

function renderTemplateFile($file){

    global $layout;

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

    $file = str_replace("/", "___", $file);

    $templatedContent = $layout;

    $templatedContent = str_replace("__PAGEBLOCK__", $newContent, $templatedContent);
    $templatedContent = str_replace("__TITLEBLOCK__", $title, $templatedContent);
    $templatedContent = str_replace("__DESCRIPTIONBLOCK__", $description, $templatedContent);

    $filePath = "./var/cache/views/$file";

    if(!file_exists($filePath)){
        fopen($filePath, "w");
    }

    file_put_contents($filePath, $templatedContent);

}

readFolder($templateFolder);
render();
