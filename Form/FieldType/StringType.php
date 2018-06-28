<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\FieldType;

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

        if (null !== $options['min'] || null !== $options['max']) {
            $options['constraints'][] = new Constraints\Length([
                'min' => $options['min'],
                'max' => $options['max'],
            ]);
        }

        if ($options['placeholder']) {
            $options['attr']['placeholder'] = $options['placeholder'];
        }

        $type = $options['multi_line'] ? TextareaType::class : TextType::class;

        unset($options['multi_line']);
        unset($options['min']);
        unset($options['max']);
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
            'min' => null,
            'max' => null,
        ]);

        $resolver->setAllowedTypes('multi_line', 'bool');
        $resolver->setAllowedTypes('placeholder', 'string');
        $resolver->setAllowedTypes('min', ['null', 'int']);
        $resolver->setAllowedTypes('max', ['null', 'int']);
    }
}
