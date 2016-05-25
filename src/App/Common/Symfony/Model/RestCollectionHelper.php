<?php

namespace App\Common\Symfony\Model;

use App\Common\Symfony\Form\Model\AbstractFilter;
use App\Common\Doctrine\EntityRepository;

class RestCollectionHelper{
    private $items;
    private $count;
    private $repo;
    private $filter;
    private $route;

    public function __construct(EntityRepository $repo, AbstractFilter $filter)
    {
        $this->repo = $repo;
        $this->filter = $filter;
    }

    public function getItems()
    {
        if(!isset($this->items)) {
            $this->items = $this->repo->get($this->filter->getQueryParameters());
        }
        return $this->items;
    }

    public function getCount()
    {
        if(!isset($this->count)) {
            $criteria = $this->filter->getQueryParameters();
            unset($criteria['@limit']);
            unset($criteria['@offset']);
            $this->count = $this->repo->getCount($criteria);
        }
        return $this->count;
    }
    public function getNextOffset(){
        $f = $this->filter;
        $limit = $f->getLimit();
        $oldOffset = $f->getOffset();
        $offset = $oldOffset + $limit;
        $count = $this->getCount();
        if($offset == $oldOffset || $count <= $offset){
            return false;
        }
        return $offset;
    }

    public function getPrevOffset(){
        $f = $this->filter;
        $limit = $f->getLimit();
        $oldOffset = $f->getOffset();
        $offset = $oldOffset - $limit;
        $count = $this->getCount();
        if($offset == $oldOffset || $count == 0 || 0 > $offset){
            return false;
        }
        return $offset;
    }

    public function hasNext()
    {
        return $this->getNextOffset() !== false;
    }

    public function hasPrev()
    {
        return $this->getPrevOffset() !== false;
    }

    public function getParams()
    {
        return $this->filter->getRouteParameters();
    }


    public function getParamsNext()
    {
        $offset = $this->getNextOffset();
        if($offset !== false) {
            return array_merge($this->filter->getRouteParameters(), compact('offset'));
        }
    }

    public function getParamsPrev()
    {
        $offset = $this->getPrevOffset();
        if($offset !== false) {
            return array_merge($this->filter->getRouteParameters(), compact('offset'));
        }
    }

    public function getFilter(){
        return $this->filter;
    }
}