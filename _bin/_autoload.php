<?php

use App\rendering\Template;

namespace App\rendering;

require_once("_bin/functions.php");
require_once("_bin/env.php");

// require_once("_bin/rendering.php");
require_once("_bin/rendering/Template.php");

require_once("_bin/exceptions/exceptions_handler.php");
require_once("_bin/db.php");
require_once("_bin/dev/minifyJS.php");
require_once("_bin/dev/scssphp.php");

if(!is_dir("build")){
    mkdir("build");
}

new Template("homepage", "test.html.twig");

include($env["RENDERED_PAGE"]);