<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\FieldType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as SymfonyEntityType;

/**
 * Class EntityType.
 *
 * @author Tomasz Gemza
 */
class EntityType extends ChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(array $options): FormBuilderInterface
    {
        $options = $this->optionsResolver->resolve($options);

        if ($options['multiple'] && (null !== $options['min'] || null !== $options['max'])) {
            $options['constraints'][] = new Constraints\Count([
                'min' => $options['min'],
                'max' => $options['max'],
            ]);
        }

        unset($options['min']);
        unset($options['max']);

        $this->isMultiple = $options['multiple'];

        return $this->createFormBuilder(SymfonyEntityType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => null,
            'choice_label' => null,
            'query_builder' => null,
            'expanded' => false,
            'multiple' => false,
            'min' => null,
            'max' => null,
        ]);

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('choice_label', ['null', 'callable', 'Symfony\Component\PropertyAccess\PropertyPath']);
        $resolver->setAllowedTypes('query_builder', ['null', 'callable', 'Doctrine\ORM\QueryBuilder']);
        $resolver->setAllowedTypes('expanded', 'bool');
        $resolver->setAllowedTypes('multiple', 'bool');
        $resolver->setAllowedTypes('min', ['null', 'int']);
        $resolver->setAllowedTypes('max', ['null', 'int']);

        $resolver->setRequired('class');
        $resolver->setRequired('choice_label');
    }
}
