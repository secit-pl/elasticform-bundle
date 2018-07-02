<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

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
     * Get type name. This value will be used as a unique type identifier.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get form builder.
     *
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function getFormBuilder(array $options): FormBuilderInterface;
}
