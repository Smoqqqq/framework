<?php

namespace CascadIO;

use ReflectionClass;
use App\Controllers\TestController;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;


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
            require_once("src/Controller/TestController.php");

            require_once("_bin/Annotations/read.php");

            /*  require_once("_bin/db.php");
                require_once("_bin/dev/minifyJS.php");
                require_once("_bin/dev/scssphp.php"); */


            $controller = new TestController();

            $controller->homepage();
        } catch (\Error $e) {
            // custom error handling
            trigger_error($e);
        }
    }
}

new Kernel();
