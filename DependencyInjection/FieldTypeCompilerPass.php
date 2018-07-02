<?php

namespace SecIT\ElasticFormBundle\DependencyInjection;

use SecIT\ElasticFormBundle\ElasticForm\FormFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FieldTypeCompilerPass.
 *
 * @author Tomasz Gemza
 */
class FieldTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(FormFactory::class)) {
            return;
        }

        $definition = $container->findDefinition(FormFactory::class);
        $taggedServices = $container->findTaggedServiceIds('elasticform.field_type');
        foreach (array_keys($taggedServices) as $id) {
            $definition->addMethodCall('addFiledType', [new Reference($id)]);
        }
    }
}
