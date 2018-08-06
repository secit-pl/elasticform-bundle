<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType as SymfonyDateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class DateType.
 *
 * @author Tomasz Gemza
 */
class DateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function transform($value, array $options = [])
    {
        if ($value) {
            return new \DateTime($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value, array $options = [])
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);
        $options['widget'] = 'single_text';
        $options['constraints'][] = new Constraints\Date();

        if (null !== $options['min_date']) {
            $options['constraints'][] = new Constraints\Callback([
                'callback' => function ($value, ExecutionContextInterface $context) use ($options) {
                    if (is_string($value) && $value && new \DateTime($value) < new \DateTime($options['min_date'])) {
                        $rangeConstraint = new Constraints\Range(['min' => 0]);
                        $context->addViolation($rangeConstraint->minMessage, [
                            '{{ limit }}' => $options['min_date'],
                        ]);
                    }
                }
            ]);
        }
        
        if (null !== $options['max_date']) {
            $options['constraints'][] = new Constraints\Callback([
                'callback' => function ($value, ExecutionContextInterface $context) use ($options) {
                    if (is_string($value) && $value && new \DateTime($value) > new \DateTime($options['max_date'])) {
                        $rangeConstraint = new Constraints\Range(['max' => 0]);
                        $context->addViolation($rangeConstraint->maxMessage, [
                            '{{ limit }}' => $options['max_date'],
                        ]);
                    }
                }
            ]);
        }

        if ($options['placeholder']) {
            $options['attr']['placeholder'] = $options['placeholder'];
        }

        unset($options['min_date']);
        unset($options['max_date']);
        unset($options['placeholder']);

        return $this->createFormBuilder(SymfonyDateType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'placeholder' => '',
            'min_date' => null,
            'max_date' => null,
        ]);

        $resolver->setAllowedTypes('placeholder', ['null', 'string']);
        $resolver->setAllowedTypes('min_date', ['null', 'string']);
        $resolver->setAllowedTypes('max_date', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $form = parent::getConfigurationFormBuilder($data, $options);
        $form->get('options')
            ->add('placeholder', TextType::class, [
                'label' => 'field.date.options_form.placeholder.label',
                'required' => false,
            ])
            ->add('min_date', SymfonyDateType::class, [
                'label' => 'field.date.options_form.min_date.label',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('max_date', SymfonyDateType::class, [
                'label' => 'field.date.options_form.max_date.label',
                'required' => false,
                'widget' => 'single_text',
            ]);

        $form->get('options')->get('min_date')->addModelTransformer($this->getModelTransformer());
        $form->get('options')->get('max_date')->addModelTransformer($this->getModelTransformer());

        return $form;
    }
}
