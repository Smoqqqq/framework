<?php

use Annotations\Route;
use App\Controllers\TestController;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

// Deprecated and will be removed in 2.0 but currently needed
AnnotationRegistry::registerLoader('class_exists');

$TestController = new TestController();

$method = new ReflectionMethod(TestController::class, "homepage");

$reader = new FileCacheReader(
    new AnnotationReader(),
    $env["DOCTRINE_CACHE"],
    $debug = true
);
$route = $reader->getMethodAnnotation(
    $method,
    Route::class
);

$controllers = array();

function getControllers($folder = null)
{
    global $env;
    global $controllers;
    if ($folder === null) $folder = $env['SRC'];
    $foldersToRead = array();
    $files = array_diff(scandir($folder), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$folder/$file";
        if (is_dir($filePath)) {
            array_push($foldersToRead, $filePath);
        } else {
            $controllerContent = file_get_contents($filePath);
            $class = explode("class ", $controllerContent)[1];
            $class = explode(" ", $class)[0];
            $controller = array(
                "filepath" => $filePath,
                "class" => $class
            );
            array_push($controllers, $controller);
        }
    }
    foreach ($foldersToRead as $folder) {
        getControllers($folder);
    }
}

$reflectionController = new ReflectionClass(TestController::class);

$methods = $reflectionController->getMethods();

$routes = array();

getControllers();

dd($controllers);

foreach($methods as $method){
    $method = new ReflectionMethod(TestController::class, $method->name);
    $route = $reader->getMethodAnnotation(
        $method,
        Route::class
    );

    if($route !== null){
        array_push($routes, $route);
    }
}