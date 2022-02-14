<?php

namespace App\Routes;

use ReflectionClass;
use Annotations\Route;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\AnnotationReader;

class RouteFinder
{

    public function __construct()
    {
        global $env;

        if($env["ENV"] === "dev") $this->getRoutes();
        else $this->getRoutesFromCache();
    }

    public function getRoutesFromCache(){
        global $env;
        require_once($env["ROUTES"]);
        $this->routes = $routes;
    }

    public function getRoutes()
    {
        global $env;

        $controllers = getControllers();

        $routes = array();

        foreach ($controllers as $controller) {

            $namespace = "App\Controllers";

            $class = "$namespace\\$controller[class]";

            $source = "$controller[filepath]";

            require_once($source);

            $instance = new $class;

            $reflectionController = new ReflectionClass($instance);

            $methods = $reflectionController->getMethods();

            $reader = new FileCacheReader(
                new AnnotationReader(),
                $env["DOCTRINE_CACHE"],
                $debug = true
            );

            foreach ($methods as $method) {
                $routeMethod = $reader->getMethodAnnotation(
                    $method,
                    Route::class
                );

                if ($routeMethod !== null) {
                    $route = array(
                        "name" => $routeMethod->name,
                        "route" => $routeMethod->route,
                        "controller" => $class,
                        "controller_path" => $controller["filepath"]
                    );
                    array_push($routes, $route);
                }
            }
        }

        $this->routes = $routes;

        $this->saveRoutes();
    }

    public function saveRoutes()
    {
        global $env;
        $content = "<?php " . '$routes' . "=array(";
        foreach ($this->routes as $route) {
            $content .= "array('name' => '$route[name]','route' => '$route[route]','controller' => '$route[controller]','controller_path' => '$route[controller_path]'),";
        }
        $content .= ");";
        file_put_contents($env["ROUTES"], $content);
    }

    function findCurrent()
    {
        $slug = "/" . str_replace(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
        foreach ($this->routes as $route) {
            if ($route["route"]  === $slug) {
                return $route;
            }
        }
    }
}

new RouteFinder();
