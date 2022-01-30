<?php

namespace App\Controllers;

use Annotations\Route;
use CascadIO\controllers\Controller;


class TestController extends Controller
{
    /**
     * @Route(route="/", name="homepage")
     */
    public function homepage()
    {
        $this->render("homepage.html.twig");
    }
}
