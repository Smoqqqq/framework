<?php

namespace CascadIO\rendering;

class Routes
{

    public function __construct()
    {
        global $env;
        if ($env["ENV"] === "dev") {
            $this->getRoutes();
            $this->getControllers($env["CONTROLLERS_FOLDER"]);
            $this->saveRoutes();
        }
    }

    private function saveRoutes()
    {
        global $env;
        if (!file_exists($env["ROUTES"])) {
            fopen($env["ROUTES"], "w");
        }
        $content = "<?php \n" . '$routes' . " = array(\n";
        foreach ($this->controllers as $controller) {
            foreach ($controller["routes"] as $controllerRoute) {
                foreach ($this->routes as $route) {
                    if ($env["TEMPLATES_FOLDER"] . "/" . $controllerRoute["rendered_file"] === $route["twig_path"]) {

                        $route = "'$controllerRoute[route]' => array(
                            'route'             => '$route[route]', 
                            'twig_path'         => '$route[twig_path]',
                            'namespace'         => '$controller[namespace]',
                            'file'              => '$controllerRoute[file]',
                            'controller_route'  => '$controllerRoute[route]',
                            'function'          => '$controllerRoute[function]',
                            'rendered_file'     => '$controllerRoute[rendered_file]' 
                        ),\n";

                        $content .= $route;
                    } else {
                        d([$controllerRoute, $route]);
                    }
                }
            }
        }
        $content .= ");";
        file_put_contents($env["ROUTES"], $content);
    }

    public function getCurrentRoute()
    {
        global $env;

        $slug = $_SERVER["REQUEST_URI"];
        $dir = explode("\\", getcwd());
        $dir = $dir[count($dir) - 1];
        $slug = str_replace("/$dir/", "", $slug);

        $slug = "/$slug";

        // TODO: find routes from var/cache/routes.php file 

        foreach ($this->controllers as $controllerRoute) {
            foreach ($controllerRoute["routes"] as $route) {
                if ($route["route"] === $slug) {

                    $class = "$controllerRoute[namespace]\\$controllerRoute[controller_name]";

                    require_once($route["file"]);

                    $controller = new $class();

                    $controller->setRoutes($this->routes);

                    $functioncall = $route["function"];

                    $controller->$functioncall();

                    return $route;
                }
            }
        }
        return false;
    }

    public function getControllers($folder)
    {
        global $env;

        $foldersToRead = array();
        $files = array_diff(scandir($folder), array('.', '..'));

        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                array_push($foldersToRead, $filePath);
            } else {
                $controller = explode("$env[CONTROLLERS_FOLDER]/", $filePath)[1];
                $controller = explode(".php", $controller)[0];

                $controllerContent = file_get_contents($filePath);

                $routes = explode('@Route("', $controllerContent);

                $namespace = explode("namespace ", $controllerContent)[1];
                $namespace = explode(";", $namespace)[0];


                array_splice($routes, 0, 1);

                $controllerRoutes = array();

                foreach ($routes as $route) {
                    $function = explode("(", explode("function ", $route)[1])[0];

                    $functionBody = explode("function $function", $controllerContent)[1];
                    if (count(explode('$this->render("', $functionBody)) >= 1) {

                        $renderedFile = explode('$this->render("', $functionBody)[1];
                        $renderedFile = explode('")', $renderedFile)[0];
                    } else {
                        $renderedFile = false;
                    }

                    $route = array(
                        "file"          => "$env[CONTROLLERS_FOLDER]/$file",
                        "route"         => explode('"', $route)[0],
                        "function"      => $function,
                        "rendered_file" => $renderedFile
                    );
                    array_push($controllerRoutes, $route);
                }

                $this->controllers[$controller] = array(
                    "namespace" => $namespace,
                    "controller_name" => $controller,
                    "routes" => $controllerRoutes
                );
            }
        }
        foreach ($foldersToRead as $folder) {
            $this->getControllers($folder);
        }
    }

    function getRoutes($folder = null)
    {
        global $env;
        if ($folder === null) $folder = $env['TEMPLATES_FOLDER'];
        $foldersToRead = array();
        $files = array_diff(scandir($folder), array('.', '..'));
        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                array_push($foldersToRead, $filePath);
            } else {
                $route = explode("$env[TEMPLATES_FOLDER]/", $filePath)[1];
                $route = explode(".", $route)[0];
                $this->routes[$filePath] = array(
                    "twig_path" => $filePath,
                    "route" => "/" . $route
                );
            }
        }
        foreach ($foldersToRead as $folder) {
            $this->getRoutes($folder);
        }
    }
}
