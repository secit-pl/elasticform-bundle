<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
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
    public function transform($value, array $options = [])
    {
        return '1' === $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value, array $options = [])
    {
        return $value ? '1' : '0';
    }

    /**
     * {@inheritdoc}
     */
    public function valueToString(AbstractAttribute $attribute, $value): string
    {
        return $this->translator->trans(
            'field.yes_no.string_value.'.($value ? 'yes' : 'no'),
            [],
            'elasticform'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        return $this->createFormBuilder(CheckboxType::class, $this->optionsResolver->resolve($options));
    }
}
