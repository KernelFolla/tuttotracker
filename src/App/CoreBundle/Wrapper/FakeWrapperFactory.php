<?php

namespace App\CoreBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Vich\UploaderBundle\Twig\Extension\UploaderExtension;

class FakeWrapperFactory extends AbstractWrapperFactory
{
    public function wrap($data)
    {
        return $data;
    }
}