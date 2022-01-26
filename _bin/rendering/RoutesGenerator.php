<?php

namespace App\rendering;

class RoutesGenerator
{

    public function __construct()
    {
        global $env;
        $this->routes = [];
        $this->renderingPath = $env["build"];
        $this->templatesFolder = $env["TEMPLATES_FOLDER"];
        $this->getFiles();
    }

    private function getFiles($folder = null)
    {
        if($folder === null) $folder = $this->templatesFolder;
        $foldersToRead = array();
        $files = array_diff(scandir($folder), array('.', '..'));
        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                array_push($foldersToRead, $filePath);
            } else {
                $renderPath = "$this->renderingPath/" . str_replace("/", "___", $filePath);
                $this->routes[$filePath] = array(
                    "twig_path" => $filePath,
                    "route" => $filePath,
                    // TODO: remove extension
                    "rendered_file" => $renderPath
                );
            }
        }
        foreach ($foldersToRead as $folder) {
            $this->getFiles($folder);
        }
        dd($this->routes);
    }

    public function getRoutes(){
        return $this->routes;
    }

    public function getCurrentRoute(){
        $slug = $_SERVER["REQUEST_URI"];
        $dir = explode("\\", getcwd());
        $dir = $dir[count($dir) - 1];
        $slug = str_replace("/$dir/", "", $slug);
        return $slug;
    }

    function renderCurrentPage(){
        $currentRoute = $this->getCurrentRoute();
        $currentPage = array_search($currentRoute, $this->routes);
    }
}
