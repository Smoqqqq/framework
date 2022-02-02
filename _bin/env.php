<?php

$variables = file_get_contents("./.env");
$variables = explode("\n", $variables);

$env = array();

for ($i = 0; $i < count($variables); $i++) {
    if (substr($variables[$i], 0, 1) != "#" && strlen($variables[$i]) > 1) {
        $key = explode("=", $variables[$i])[0];
        $value = explode("=", $variables[$i])[1];
        $env[$key] = str_replace(["\r", "\n"], "", $value);
    }
}

$defaults = [
    "TEMPLATES_FOLDER"      => "templates",
    "DB_NAME"               => "",
    "DB_DRIVER"             => "pdo_mysql",
    "DB_USER"               => "",
    "DB_PASS"               => "",
    "TWIG_CACHE"            => "var/cache/Twig",
    "DOCTRINE_CACHE"        => "var/cache/Doctrine",
    "ERROR_LOG"             => "/var/logs/php_error.log",
    "SRC"                   => "src",
    "CONTROLLERS"                   => "src/Controller",
    "ROUTES"                => "var/cache/routes.php",
    "ENTITIES"              => "src/Entity"
];

function defaultEnv()
{
    global $defaults;
    global $env;
    foreach ($defaults as $var => $value) {
        if (!isset($env[$var]) || ctype_space($env[$var])) {
            $env[$var] = $value;
        }
    }
}

defaultEnv();