<?php

namespace App\CoreBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;

abstract class AbstractWrapperFactory implements WrapperFactory
{
    /** @var  WrapperProvider */
    private $provider;

    /**
     * @DI\InjectParams({
     *     "provider"  = @DI\Inject("app.wrapper_provider"),
     * })
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return WrapperProvider
     */
    protected function getProvider(){
        return $this->provider;
    }

    abstract public function wrap($date);
}