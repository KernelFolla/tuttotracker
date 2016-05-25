<?php

namespace App\Common\Symfony\Action;

class GenericApiListAction extends AbstractApiListAction
{
    /**
     * @param mixed $dataClass
     * @return $this
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;

        return $this;

    }

    /**
     * @param mixed $filterClass
     * @return $this
     */
    public function setFilterClass($filterClass)
    {
        $this->filterClass = $filterClass;

        return $this;

    }

    /**
     * @param mixed $filterTypeName
     * @return $this
     */
    public function setFilterTypeName($filterTypeName)
    {
        $this->filterTypeName = $filterTypeName;

        return $this;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function setWrapperCallback($callback)
    {
        $this->wrapperCallback = $callback;

        return $this;
    }

    public function setFilterTypeOptions($options)
    {
        $this->filterTypeOptions = $options;

        return $this;
    }
}