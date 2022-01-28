<?php

namespace App\rendering;

use App\rendering\Template;

class RoutesGenerator
{

    public function __construct()
    {
        global $env;
        $this->routes = [];
        $this->renderingPath = $env["BUILD"];
        $this->templatesFolder = $env["TEMPLATES_FOLDER"];
        $this->getFiles();
    }

    private function getFiles($folder = null)
    {
        global $env;
        if ($folder === null) $folder = $this->templatesFolder;
        $foldersToRead = array();
        $files = array_diff(scandir($folder), array('.', '..'));
        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                array_push($foldersToRead, $filePath);
            } else {
                $renderPath = "$this->renderingPath/" . str_replace("/", "___", $filePath);
                $route = explode("$env[TEMPLATES_FOLDER]/", $filePath)[1];
                $route = explode(".", $route)[0];
                $this->routes[$filePath] = array(
                    "twig_path" => $filePath,
                    "route" => $route,
                    "rendered_file" => $renderPath
                );
            }
        }
        foreach ($foldersToRead as $folder) {
            $this->getFiles($folder);
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getCurrentRoute()
    {
        $slug = $_SERVER["REQUEST_URI"];
        $dir = explode("\\", getcwd());
        $dir = $dir[count($dir) - 1];
        $slug = str_replace("/$dir/", "", $slug);

        foreach ($this->routes as $route) {
            if ($route["route"] === $slug) return $route;
        }
        return false;
    }

    function renderCurrentPage()
    {
        $currentRoute = $this->getCurrentRoute();
        $template = new Template($currentRoute);
        $template->render();
    }
}
