<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType as SymfonyCountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Validator\Constraints;

/**
 * Class CountryType.
 *
 * @author Tomasz Gemza
 */
class CountryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function transform($value, array $options = [])
    {
        if ($options['multiple']) {
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
    public function reverseTransform($value, array $options = [])
    {
        if ($options['multiple']) {
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
        $regions = Intl::getRegionBundle();
        $countries = [];
        foreach ($values as $countryCode) {
            $country = $regions->getCountryName($countryCode);
            if ($country) {
                $countries[] = $country;
            }
        }

        return implode(', ', $countries);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);
        if ($options['multiple'] && (null !== $options['min_choices'] || null !== $options['max_choices'])) {
            $options['constraints'][] = new Constraints\Count([
                'min' => $options['min_choices'],
                'max' => $options['max_choices'],
            ]);
        }

        unset($options['min_choices']);
        unset($options['max_choices']);

        return $this->createFormBuilder(SymfonyCountryType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => false,
            'multiple' => false,
            'min_choices' => null,
            'max_choices' => null,
        ]);

        $resolver->setAllowedTypes('expanded', 'bool');
        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('min_choices', ['null', 'int']);
        $resolver->setAllowedTypes('max_choices', ['null', 'int']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $form = parent::getConfigurationFormBuilder($data, $options);
        $form->get('options')
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
