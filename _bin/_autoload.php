<?php

namespace CascadIO;

use App\Routes\RouteFinder;

require_once("_bin/env.php");
require_once("_bin/exceptions/exceptions_handler.php");

class Kernel
{

    public function __construct()
    {

        require_once("./vendor/autoload.php");
        require_once("_bin/functions.php");
        require_once("_bin/Annotations/Route.php");
        require_once("_bin/controllers/Controller.php");
        require_once("_bin/controllers/GetControllers.php");
        require_once("_bin/Routes/RouteFinder.php");
        require_once("_bin/Doctrine/GetEntities.php");

        /*  require_once("_bin/db.php");
            require_once("_bin/dev/minifyJS.php");
            require_once("_bin/dev/scssphp.php"); */

        $this->autoloadClass();

        $this->getCurrentRoute();
    }

    public function getCurrentRoute()
    {
        $routeFinder = new RouteFinder();

        $currentRoute = $routeFinder->findCurrent();
        $function = $currentRoute["name"];
        $source = "$currentRoute[controller_path]";
        require_once($source);
        $instance = new $currentRoute["controller"];
        $instance->$function();
    }

    public static function loadClass($class)
    {
        $files = array(
            $class . '.php',
            str_replace('_', '/', $class) . '.php',
        );
        foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $base_path) {
            foreach ($files as $file) {
                $path = "$base_path/$file";
                if (file_exists($path) && is_readable($path)) {
                    include_once $path;
                    return;
                }
            }
        }
    }

    public function autoloadClass()
    {
        $entities = getEntities();

        foreach($entities as $entity){
            $this->loadClass(str_replace(".php", "", $entity["filepath"]));
        }
    }
}

try {
    $kernel = new Kernel();
} catch (\Error $e) {
    trigger_error($e);
}
