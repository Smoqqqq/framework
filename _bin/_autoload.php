<?php

use App\rendering\Template;
use App\rendering\RoutesGenerator;

namespace App\rendering;


require_once("_bin/functions.php");
require_once("_bin/env.php");

require_once("_bin/exceptions/exceptions_handler.php");

try {

    require_once("_bin/rendering/Template.php");
    require_once("_bin/rendering/RoutesGenerator.php");

    require_once("_bin/db.php");
    require_once("_bin/dev/minifyJS.php");
    require_once("_bin/dev/scssphp.php");

    if (!is_dir("build")) {
        mkdir("build");
    }

    $routesGenerator = new RoutesGenerator();
    $routesGenerator->renderCurrentPage();

} catch (\Error $e) {
    // custom error handling
    trigger_error($e);
}
