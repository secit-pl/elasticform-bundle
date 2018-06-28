<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\FieldType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Interface TypeInterface.
 *
 * @author Tomasz Gemza
 */
interface TypeInterface extends DataTransformerInterface
{
    /**
     * Get form builder.
     *
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function getFormBuilder(array $options): FormBuilderInterface;
}
