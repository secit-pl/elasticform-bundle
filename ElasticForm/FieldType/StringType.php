<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Class StringType.
 *
 * @author Tomasz Gemza
 */
class StringType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);

        if (null !== $options['min_length'] || null !== $options['max_length']) {
            $options['constraints'][] = new Constraints\Length([
                'min' => $options['min_length'],
                'max' => $options['max_length'],
            ]);
        }

        if ($options['placeholder']) {
            $options['attr']['placeholder'] = $options['placeholder'];
        }

        $type = $options['multi_line'] ? TextareaType::class : TextType::class;

        unset($options['multi_line']);
        unset($options['min_length']);
        unset($options['max_length']);
        unset($options['placeholder']);

        return $this->createFormBuilder($type, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multi_line' => false,
            'placeholder' => '',
            'min_length' => null,
            'max_length' => null,
        ]);

        $resolver->setAllowedTypes('multi_line', ['null', 'bool']);
        $resolver->setAllowedTypes('placeholder', ['null', 'string']);
        $resolver->setAllowedTypes('min_length', ['null', 'int']);
        $resolver->setAllowedTypes('max_length', ['null', 'int']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $form = parent::getConfigurationFormBuilder($data, $options);
        $form->get('options')
            ->add('multi_line', CheckboxType::class, [
                'label' => 'field.string.options_form.multi_line.label',
                'required' => false,
            ])
            ->add('placeholder', TextType::class, [
                'label' => 'field.string.options_form.placeholder.label',
                'required' => false,
            ])
            ->add('min_length', NumberType::class, [
                'label' => 'field.string.options_form.min_length.label',
                'required' => false,
                'constraints' => [
                    new Constraints\Range([
                        'min' => 0,
                        'max' => PHP_INT_MAX,
                    ]),
                ],
            ])
            ->add('max_length', NumberType::class, [
                'label' => 'field.string.options_form.max_length.label',
                'required' => false,
                'constraints' => [
                    new Constraints\Range([
                        'min' => 0,
                        'max' => PHP_INT_MAX,
                    ]),
                ],
            ]);

        return $form;
    }
}
