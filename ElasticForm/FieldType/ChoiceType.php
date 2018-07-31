<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Form\AttributeConfiguration\KeyValueType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as SymfonyChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $arguments = func_get_args();
        if (isset($arguments[1]) && $arguments[1]['multiple']) {
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
        $arguments = func_get_args();
        if (isset($arguments[1]) && $arguments[1]['multiple']) {
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
    public function valueToString(AbstractAttribute $attribute, $value): string
    {
        $options = $this->optionsResolver->resolve(['name' => 'x', 'label' => 'x'] + $attribute->getOptions());
        $choices = [];
        foreach ($options['choices'] as $choice) {
            $choices[$choice['key']] = $choice['value'];
        }

        $values = is_array($value) ? $value : [$value];
        $values = array_flip($values);

        return implode(', ', array_intersect_key($choices, $values));
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);
        $options['choice_loader'] = new CallbackChoiceLoader(function () use ($options) {
            $choices = [];
            foreach ($options['choices'] as $choice) {
                $choices[$choice['value']] = $choice['key'];
            }

            return $choices;
        });

        if ($options['multiple'] && (null !== $options['min_choices'] || null !== $options['max_choices'])) {
            $options['constraints'][] = new Constraints\Count([
                'min' => $options['min_choices'],
                'max' => $options['max_choices'],
            ]);
        }

        unset($options['choices']);
        unset($options['min_choices']);
        unset($options['max_choices']);

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
            'min_choices' => null,
            'max_choices' => null,
        ]);

        $resolver->setAllowedTypes('choices', ['array', '\Traversable']);
        $resolver->setAllowedTypes('expanded', 'bool');
        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('min_choices', ['null', 'int']);
        $resolver->setAllowedTypes('max_choices', ['null', 'int']);

        $resolver->setRequired('choices');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $form = parent::getConfigurationFormBuilder($data, $options);
        $form->get('options')
            ->add('choices', CollectionType::class, [
                'label' => 'field.choice.options_form.choices.label',
                'required' => false,
                'entry_type' => KeyValueType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('expanded', CheckboxType::class, [
                'label' => 'field.choice.options_form.expanded.label',
                'required' => false,
            ])
            ->add('multiple', CheckboxType::class, [
                'label' => 'field.choice.options_form.multiple.label',
                'required' => false,
            ])
            ->add('min_choices', NumberType::class, [
                'label' => 'field.choice.options_form.min_choices.label',
                'required' => false,
                'constraints' => [
                    new Constraints\Range([
                        'min' => 0,
                        'max' => PHP_INT_MAX,
                    ]),
                ],
            ])
            ->add('max_choices', NumberType::class, [
                'label' => 'field.choice.options_form.max_choices.label',
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
