<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form;

use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Form\AttributeConfiguration\KeyValueType;
use SecIT\ElasticFormBundle\Form\AttributeConfiguration\TranslationType;
use SecIT\ElasticFormBundle\Helper\EntityMetadataTrait;
use SecIT\EntityTranslationBundle\Form\Type\ResourceTranslationsType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AttributeConfigurationType.
 *
 * @author Tomasz Gemza
 */
class AttributeConfigurationType extends AbstractType
{
    use EntityMetadataTrait;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * AttributeGroupType constructor.
     *
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var AbstractAttribute $attribute */
        $attribute = $builder->getData();
        if (!$attribute || !$attribute->getType()) {
            throw new \Exception('Attribute type should be declared before creating the configuration form.');
        }

        $builder
            ->add('type', HiddenType::class, [
                'constraints' => [
                    new NotBlank(),
                    new EqualTo($attribute->getType()),
                ],
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'form.attribute_configuration.fields.translations.label',
                'entry_type' => TranslationType::class,
                'entry_options' => [
                    'data_class' => $this->getTargetEntityClass(get_class($attribute), 'translations'),
                ],
            ])
            ->add('attributeKey', TextType::class, [
                'label' => 'form.attribute_configuration.fields.attribute_key.label',
                'required' => true,
                'disabled' => null !== $attribute->getId(),
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'form.attribute_configuration.fields.required.label',
                'required' => false,
            ])
            ->add('options', FormType::class, [
                'label' => 'form.attribute_configuration.fields.options.label',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'form.attribute_configuration.fields.save.label',
            ]);

        $builder->get('options')
            ->add('attr', CollectionType::class, [
                'label' => 'form.attribute_configuration.fields.options.fields.attr.label',
                'required' => false,
                'entry_type' => KeyValueType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('label_attr', CollectionType::class, [
                'label' => 'form.attribute_configuration.fields.options.fields.label_attr.label',
                'required' => false,
                'entry_type' => KeyValueType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);

        $builder->get('options')->get('attr')->addModelTransformer($this->getKeyValueCollectionModelTransformer());
        $builder->get('options')->get('label_attr')->addModelTransformer($this->getKeyValueCollectionModelTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbstractAttribute::class,
            'translation_domain' => 'elasticform',
        ]);
    }

    /**
     * Get key value collection model transformer.
     *
     * @return CallbackTransformer
     */
    protected function getKeyValueCollectionModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($data) {
                if (null === $data) {
                    return null;
                }

                foreach ($data as $key => &$value) {
                    $value = [
                        'key' => $key,
                        'value' => $value,
                    ];
                }

                return $data;
            },
            function ($data) {
                if (null === $data) {
                    return null;
                }

                $return = [];
                foreach ($data as $row) {
                    $return[$row['key']] = $row['value'];
                }

                return $return;
            }
        );
    }
}
