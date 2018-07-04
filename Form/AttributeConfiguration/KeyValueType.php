<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\AttributeConfiguration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class KeyValueType.
 *
 * @author Tomasz Gemza
 */
class KeyValueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('key', TextType::class, [
                'label' => 'form.key_value.fields.key.label',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('value', TextType::class, [
                'label' => 'form.key_value.fields.value.label',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'elasticform',
        ]);
    }
}
