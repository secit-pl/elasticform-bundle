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
     * Get form builder. This method should be used to get the form builder used to
     * create a form which will be used as a data input.
     *
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function getFormBuilder(array $options): FormBuilderInterface;

    /**
     * Get configuration form builder. This method should be used to create a form
     * builder used to setup and configure specivied form field.
     *
     * @param null|mixed $data
     * @param array      $options
     *
     * @return FormBuilderInterface
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface;
}
