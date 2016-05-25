<?php

namespace App\Common\Symfony\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Kf\KitBundle\Symfony\Controller\DoctrineORMHelper;

class RestController extends FOSRestController{
    use DoctrineORMHelper;

    protected function wrap($data){
        return $this->get('app.wrapper_provider')->wrap($data);
    }
}