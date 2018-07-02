<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use SecIT\ElasticFormBundle\ElasticForm\FieldType\ChoiceType\ChoiceInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Class ChoiceType.
 *
 * @author Tomasz Gemza
 */
class ChoiceType extends AbstractType
{
    /**
     * @var bool
     */
    protected $isMultiple = false;

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($this->isMultiple) {
            if (!$value) {
                return [];
            }

            return is_array($value) ? $value : [$value];
        }

        return $value ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($this->isMultiple) {
            if (!$value) {
                return [];
            }

            return is_array($value) ? $value : [$value];
        }

        return $value ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);
        $options['choice_label'] = function ($value) {
            return $this->getChoiceLabel($value);
        };

        $options['choice_value'] = function ($value) {
            return $this->getChoiceValue($value);
        };

        if ($options['multiple'] && (null !== $options['min'] || null !== $options['max'])) {
            $options['constraints'][] = new Constraints\Count([
                'min' => $options['min'],
                'max' => $options['max'],
            ]);
        }

        unset($options['min']);
        unset($options['max']);

        $this->isMultiple = $options['multiple'];

        return $this->createFormBuilder(SymfonyChoiceType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => null,
            'expanded' => false,
            'multiple' => false,
            'min' => null,
            'max' => null,
        ]);

        $resolver->setAllowedTypes('choices', ['array', '\Traversable']);
        $resolver->setAllowedTypes('expanded', 'bool');
        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('min', ['null', 'int']);
        $resolver->setAllowedTypes('max', ['null', 'int']);

        $resolver->setRequired('choices');
    }

    /**
     * Get choice label.
     *
     * @param mixed $value
     *
     * @return null|string|mixed
     */
    protected function getChoiceLabel($value)
    {
        if ($value instanceof ChoiceInterface) {
            return $value->getFormChoiceLabel();
        }

        return $value;
    }

    /**
     * Get choice value.
     *
     * @param mixed $value
     *
     * @return int|null|mixed
     */
    protected function getChoiceValue($value)
    {
        if ($value instanceof ChoiceInterface) {
            return $value->getFormChoiceValue();
        }

        return $value;
    }
}
