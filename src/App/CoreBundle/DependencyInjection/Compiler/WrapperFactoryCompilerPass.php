<?php

namespace App\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WrapperFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('app.wrapper_provider')) {
            return;
        }

        $definition = $container->getDefinition(
            'app.wrapper_provider'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'app.wrapper_factory'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addFactory',
                    array(new Reference($id), $attributes['class'])
                );
            }
        }
    }
}