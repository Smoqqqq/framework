<?php

namespace App\Controllers;

use CascadIO\controllers\Controller;

class TestController extends Controller
{

   /**
    * @Route("/", name="homepage")
    */
    public function index(){
        $this->showTest = true;
        $this->isConfirmed = false;
        $this->render("homepage.html.twig");
    }
    
   /**
    * @Route("/homepage", name="homepage")
    */
    public function homepage(){
        $this->render("page-two.html.twig");
    }

}