<?php

namespace SecIT\ElasticFormBundle;

use SecIT\ElasticFormBundle\DependencyInjection\FieldTypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ElasticFormBundle.
 *
 * @author Tomasz Gemza
 */
class ElasticFormBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FieldTypeCompilerPass());
    }
}

