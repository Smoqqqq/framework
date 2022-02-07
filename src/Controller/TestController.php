<?php

namespace App\Controllers;

use Annotations\Route;
use App\Entities\User;
use CascadIO\controllers\Controller;


class TestController extends Controller
{
    /**
     * @Route(route="/", name="homepage")
     */
    public function homepage()
    {
        $em = $this->getEm();
        $user = new User();
        $user->setName("Paul");
        $em->persist($user);
        $em->flush();
        $this->render("homepage.html.twig");
    }
}
