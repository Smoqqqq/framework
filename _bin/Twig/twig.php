<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$debug = ($env["ENV"] === "dev") ? true : false;

$loader = new \Twig\Loader\FilesystemLoader($env["TEMPLATES_FOLDER"]);
$twig = new \Twig\Environment($loader, [
    'cache' => $env["TWIG_CACHE"],
    'debug' => $debug
]);

echo $twig->render('homepage.html.twig', ['name' => 'Fabien']);