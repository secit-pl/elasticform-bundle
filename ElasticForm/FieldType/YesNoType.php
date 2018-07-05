<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class YesNoType.
 *
 * @author Tomasz Gemza
 */
class YesNoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value === '1';
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return $value ? '1' : '0';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        return $this->createFormBuilder(CheckboxType::class, $this->optionsResolver->resolve($options));
    }
}
