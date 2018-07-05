<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Symfony\Component\Form\Extension\Core\Type\NumberType as SymfonyNumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Class NumberType.
 *
 * @author Tomasz Gemza
 */
class NumberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        return (float) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        return (float) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);

        if (null !== $options['min_value'] || null !== $options['max_value']) {
            $options['constraints'][] = new Constraints\Range([
                'min' => $options['min_value'],
                'max' => $options['max_value'],
            ]);
        }

        if ($options['placeholder']) {
            $options['attr']['placeholder'] = $options['placeholder'];
        }

        unset($options['min_value']);
        unset($options['max_value']);
        unset($options['placeholder']);

        return $this->createFormBuilder(SymfonyNumberType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'scale' => 0,
            'placeholder' => '',
            'min_value' => null,
            'max_value' => null,
        ]);

        $resolver->setAllowedTypes('scale', ['null', 'int']);
        $resolver->setAllowedTypes('placeholder', ['null', 'string']);
        $resolver->setAllowedTypes('min_value', ['null', 'int']);
        $resolver->setAllowedTypes('max_value', ['null', 'int']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $form = parent::getConfigurationFormBuilder($data, $options);
        $form->get('options')
            ->add('scale', SymfonyNumberType::class, [
                'label' => 'field.number.options_form.scale.label',
                'required' => false,
            ])
            ->add('placeholder', TextType::class, [
                'label' => 'field.number.options_form.placeholder.label',
                'required' => false,
            ])
            ->add('min_value', SymfonyNumberType::class, [
                'label' => 'field.number.options_form.min_value.label',
                'required' => false,
                'constraints' => [
                    new Constraints\Range([
                        'min' => PHP_INT_MIN,
                        'max' => PHP_INT_MAX,
                    ]),
                ],
            ])
            ->add('max_value', SymfonyNumberType::class, [
                'label' => 'field.number.options_form.max_value.label',
                'required' => false,
                'constraints' => [
                    new Constraints\Range([
                        'min' => PHP_INT_MIN,
                        'max' => PHP_INT_MAX,
                    ]),
                ],
            ]);

        return $form;
    }
}
