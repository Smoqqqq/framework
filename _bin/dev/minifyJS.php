<?php

use MatthiasMullie\Minify\Minify;
use MatthiasMullie\Minify\JS;

require_once("./_bin/dev/minify/src/Minify.php");
require_once("./_bin/dev/minify/src/JS.php");
require_once("./_bin/dev/minify/src/Exception.php");
require_once("./_bin/dev/minify/src/Exceptions/BasicException.php");
require_once("./_bin/dev/minify/src/Exceptions/FileImportException.php");
require_once("./_bin/dev/minify/src/Exceptions/IOException.php");

$path = $env["JS_WATCH_PATH"];

try {
    if (is_dir($path)) {

        $compiled_path = $env["JS_COMPILE_PATH"];

        $compiled_path_last = explode("/", $compiled_path);
        $compiled_path_last = $compiled_path_last[count($compiled_path_last) - 1];

        $JSfiles = array_diff(scandir($path), array('.', '..', $compiled_path_last));

        $minifier = new JS();

        foreach ($JSfiles as $file) {
            $source = "$path/$file";
            $minifier->add($source);

            $minifiedPath = "$path/compiled/$file";
            $minifier->minify($minifiedPath);
        }
    } else {
        throw new Exception("The provided JS_WATCH_PATH is not a valid folder !");
    }
} catch (\Exception $e) {
    throw new Exception("The provided JS_WATCH_PATH is not a valid folder !");
}
