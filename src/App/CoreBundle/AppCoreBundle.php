<?php

namespace App\CoreBundle;

use App\CoreBundle\DependencyInjection\Compiler\WrapperFactoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new WrapperFactoryCompilerPass());
    }
}
