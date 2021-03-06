<?php

namespace Oapition\DependencyInjection;

use Oapition\Action\ActionRunnerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ActionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(ActionRunnerBuilder::class)) {
            return;
        }

        $definition = $container->findDefinition(ActionRunnerBuilder::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('oapition.action');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the TransportChain service
            $definition->addMethodCall('addAction', [new Reference($id)]);
        }
    }
}