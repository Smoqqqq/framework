<?php

namespace App\controllers;

class Controller{
    public function __construct($route)
    {
        $this->route = $route;
    }
}