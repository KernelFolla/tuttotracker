<?php

namespace App\Common\Symfony\Action;

use App\Common\Symfony\Controller;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Util\Codes;

abstract class ApiBaseAction extends BaseAction
{
    /**
     * Create a view
     *
     * Convenience method to allow for a fluent interface.
     *
     * @param mixed $data
     * @param integer $statusCode
     * @param array $headers
     *
     * @return View
     */
    protected function view($data = null, $statusCode = null, array $headers = array())
    {
        return View::create($data, $statusCode, $headers);
    }


    /**
     * Create a Route Redirect View
     *
     * Convenience method to allow for a fluent interface.
     *
     * @param string $route
     * @param mixed $parameters
     * @param integer $statusCode
     * @param array $headers
     *
     * @return View
     */
    protected function routeRedirectView(
        $route,
        array $parameters = array(),
        $statusCode = Codes::HTTP_CREATED,
        array $headers = array()
    ) {
        return RouteRedirectView::create($route, $parameters, $statusCode, $headers);
    }
}