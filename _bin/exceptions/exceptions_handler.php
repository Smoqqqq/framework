<?php

ini_set("error_log", $env["ERROR_LOG"]);

function exception_error_handler($errno, $errstr, $errfile, $errline){
    error_log($errstr);
}

set_error_handler('exception_error_handler');