<?php

namespace CascadIO\controllers;

class Controller
{

    public function __construct()
    {
        global $env;
        $debug = ($env["ENV"] === "dev") ? true : false;
        $this->filesystemLoader = new \Twig\Loader\FilesystemLoader($env["TEMPLATES_FOLDER"]);
        $this->twig = new \Twig\Environment($this->filesystemLoader, [
            'cache' => $env["TWIG_CACHE"],
            'debug' => $debug
        ]);
    }

    public function render($name, array $context = [])
    {
        echo $this->twig->render($name, $context);
    }

}
