<?php

$variables = file_get_contents("./.env");
$variables = explode("\n", $variables);

$env = array();

for($i = 0; $i < count($variables); $i++){
    if(substr($variables[$i], 0, 1) != "#" && strlen($variables[$i]) > 1){
        $key = explode("=", $variables[$i])[0];
        $value = explode("=", $variables[$i])[1];
        $env[$key] = str_replace(["\r", "\n"], "", $value);
    }
}