<?php

use CascadIO\rendering\Template;
use CascadIO\rendering\RoutesGenerator;

namespace CascadIO\rendering;


require_once("_bin/env.php");
require_once("_bin/exceptions/exceptions_handler.php");


try {
    
    require_once("_bin/functions.php");

    require_once("_bin/rendering/Template.php");
    require_once("_bin/rendering/Twig.php");
    require_once("_bin/controllers/Controller.php");
    require_once("_bin/rendering/Routes.php");

    require_once("_bin/db.php");
    require_once("_bin/dev/minifyJS.php");
    require_once("_bin/dev/scssphp.php");

    if (!is_dir("build")) {
        mkdir("build");
    }

    $route = new Routes();
    $route->getCurrentRoute();

} catch (\Error $e) {
    // custom error handling
    trigger_error($e);
}
