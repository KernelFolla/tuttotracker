<?php

namespace App\Common\Symfony\Action;

use App\Common\Symfony\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseAction
{
    /** @var Controller */
    protected $controller;
    /** @var Request */
    protected $request;

    /**
     * @param Controller $controller
     *
     * @return $this
     */
    static public function create($controller)
    {
        return new static($controller);
    }

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param Controller $controller
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}