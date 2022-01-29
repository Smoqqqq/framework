<?php 
$routes = array(
'/' => array(
                            'route'             => '/homepage', 
                            'twig_path'         => 'templates/homepage.html.twig',
                            'namespace'         => 'App\Controllers',
                            'file'              => 'src/Controller/TestController.php',
                            'controller_route'  => '/',
                            'function'          => 'index',
                            'rendered_file'     => 'homepage.html.twig' 
                        ),
'/homepage' => array(
                            'route'             => '/page-two', 
                            'twig_path'         => 'templates/page-two.html.twig',
                            'namespace'         => 'App\Controllers',
                            'file'              => 'src/Controller/TestController.php',
                            'controller_route'  => '/homepage',
                            'function'          => 'homepage',
                            'rendered_file'     => 'page-two.html.twig' 
                        ),
);