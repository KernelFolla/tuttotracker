<?php

namespace App\Common\Symfony\Action;

use App\Common\Symfony\Controller\RestController;
use App\Common\Symfony\Model\RestCollectionHelper;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractApiListAction extends ApiBaseAction
{
    protected $dataClass;
    protected $filterClass;
    protected $filterTypeName;
    protected $filterTypeOptions = [];
    protected $formFactory;
    protected $filterForm;
    protected $filter;
    protected $wrapperCallback;


    protected $router;

    /**
     * @param RestController $controller
     * @return $this
     */
    public static function create($controller)
    {
        return parent::create($controller);
    }

    public function execute()
    {
        $form = $this->getFilterForm();
        $repo = $this->controller->getRepo($this->dataClass);
        $collection = new RestCollectionHelper($repo, $this->getFilter());

        if ($this->request->get('_format') == 'html') {
            return compact('collection', 'form');
        } else {
            return $this->processSerialization($collection);
        }
    }

    protected function getFilterForm()
    {
        if (empty($this->filterForm)) {
            $this->filterForm = $this->createFilterForm();
        }

        return $this->filterForm;
    }

    /**
     * @return FilterInterface
     */
    protected function getFilter()
    {
        if (!isset($this->filter)) {
            $this->filter = $this->createFilter();
        }

        return $this->filter;
    }

    protected function createFilter()
    {
        $class = $this->filterClass;

        return new $class();
    }

    /**
     * @param mixed $formFactory
     * @return $this
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    public function processSerialization(RestCollectionHelper $collection)
    {
        $route = $this->request->get('_route');
        $next = $collection->hasNext() ?
            $this->generateUrl(
                $route,
                $collection->getParamsNext()
            ) : null;
        $prev = $collection->hasPrev() ?
            $this->generateUrl(
                $route,
                $collection->getParamsPrev()
            ) : null;

        $ret = [
            'count' => $collection->getCount(),
            'next' => $next,
            'previous' => $prev,
            'results' => array_map($this->wrapperCallback, $collection->getItems()),
        ];
        $view    = $this->view($ret, Codes::HTTP_OK);
        return $view;
    }

    protected function createFilterForm()
    {
        $ret = $this->formFactory->createNamed(
            null,
            $this->filterTypeName,
            $this->getFilter(),
            $this->getFilterTypeOptions()
        );
        $ret->handleRequest($this->request);

        return $ret;
    }

    public function getFilterTypeOptions(){
        return array_merge(['method' => 'GET','csrf_protection' => false], $this->filterTypeOptions);
    }

    /**
     * @param Router $router
     *
     * @return $this
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}