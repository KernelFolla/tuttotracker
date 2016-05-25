<?php

namespace App\CoreBundle\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @FW\Route("/", name="app.front.homepage")
     * @FW\Route("/app/{label}")
     *
     * @FW\Template("front/homepage.html.twig")
     */
    public function homepageAction(Request $request)
    {

        return [
        ];
    }
}