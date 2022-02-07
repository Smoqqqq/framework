<?php

namespace App\Controllers;

use Annotations\Route;
use CascadIO\controllers\Controller;

class PageController extends Controller
{
    /**
     * @Route(route="/page", name="page_test")
     */
    public function page_test(){
        dd($this->getEm());
        return $this->render("homepage.html.twig");
    }
}