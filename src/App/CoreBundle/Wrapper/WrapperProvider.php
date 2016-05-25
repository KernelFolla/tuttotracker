<?php

namespace App\CoreBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Collections\Collection;

/**
 * @DI\Service("app.wrapper_provider")
 */
class WrapperProvider implements WrapperFactory
{
    /** @var  WrapperFactory[] */
    private $factories = [];
    private $fakeWrapperFactory;

    public function __construct()
    {
        $this->fakeWrapperFactory = new FakeWrapperFactory();
    }

    public function addFactory(WrapperFactory $factory, $class)
    {
        $this->factories[$class] = $factory;
    }

    public function wrap($data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        if (!isset($data)) {
            return null;
        } elseif (is_array($data)) {
            return array_map([$this, 'wrap'], $data);
        } elseif (is_object($data)) {
            return $this->getFactoryFor(get_class($data))->wrap($data);
        } else {
            return null;
        }
    }

    /**
     * @param $class
     * @return WrapperFactory
     */
    public function getFactoryFor($class)
    {
        if (isset($this->factories[$class]))
            return $this->factories[$class];
        else{
            foreach($this->factories as $check => $factory){
                if(is_subclass_of($class, $check)){
                    return $factory;
                }
            }
        }
        return $this->fakeWrapperFactory;
    }
}