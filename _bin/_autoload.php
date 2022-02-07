<?php

namespace CascadIO;

use App\Routes\RouteFinder;

require_once("_bin/env.php");
require_once("_bin/exceptions/exceptions_handler.php");

class Kernel
{

    public function __construct()
    {

        global $env;

        try {

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

            $this->getCurrentRoute();
        } catch (\Error $e) {
            trigger_error($e);
        }
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
}

new Kernel();
