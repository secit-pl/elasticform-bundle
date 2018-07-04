<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\AttributeGroup;

use SecIT\ElasticFormBundle\Entity\AbstractAttributeGroupTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TranslationType.
 *
 * @author Tomasz Gemza
 */
class TranslationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'form.attribute_group.translations.fields.name.label',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbstractAttributeGroupTranslation::class,
            'translation_domain' => 'elasticform',
        ]);
    }
}
