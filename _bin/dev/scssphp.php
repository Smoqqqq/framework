<?php

require_once './_bin/dependencies/scssphp/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

$path = $env["SCSS_WATCH_PATH"];
$compiled_path = $env["SCSS_COMPILE_PATH"];

try {
    if (is_dir($path)) {
        $compiled_path_last = explode("/", $compiled_path);
        $compiled_path_last = $compiled_path_last[count($compiled_path_last) - 1];

        $compiler = new Compiler();
        $compiler->setImportPaths($path);
        $compiler->setOutputStyle(OutputStyle::COMPRESSED);

        $SCSSfiles = array_diff(scandir($path), array('.', '..', $compiled_path_last));

        foreach ($SCSSfiles as $file) {
            $source = "$path/$file";
            $source = file_get_contents($source);
            $result = $compiler->compileString($source);

            $result_path = "$compiled_path/" . str_replace(".scss", ".css", $file);

            file_put_contents($result_path, $result->getCss());
        }
    } else {
        throw new Exception("The provided SCSS_WATCH_PATH is not a valid folder !");
    }
} catch (\Exception $e) {
    throw new Exception("The provided SCSS_WATCH_PATH is not a valid folder !");
}
