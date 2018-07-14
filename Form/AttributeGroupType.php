<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form;

use SecIT\ElasticFormBundle\Entity\AbstractAttributeGroup;
use SecIT\ElasticFormBundle\Entity\AbstractAttributeGroupWithSubgroups;
use SecIT\ElasticFormBundle\Form\AttributeGroup\OrderedAttributeType;
use SecIT\ElasticFormBundle\Form\AttributeGroup\OrderedGroupType;
use SecIT\ElasticFormBundle\Form\AttributeGroup\TranslationType;
use SecIT\ElasticFormBundle\Helper\EntityMetadataTrait;
use SecIT\EntityTranslationBundle\Form\Type\ResourceTranslationsType;
use SecIT\EntityTranslationBundle\Translations\TranslationLocaleProvider;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AttributeGroupType.
 *
 * @author Tomasz Gemza
 */
class AttributeGroupType extends AbstractType
{
    use EntityMetadataTrait;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var TranslationLocaleProvider
     */
    protected $translationLocaleProvider;

    /**
     * AttributeGroupType constructor.
     *
     * @param RegistryInterface         $doctrine
     * @param TranslationLocaleProvider $translationLocaleProvider
     */
    public function __construct(RegistryInterface $doctrine, TranslationLocaleProvider $translationLocaleProvider)
    {
        $this->doctrine = $doctrine;
        $this->translationLocaleProvider = $translationLocaleProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var AbstractAttributeGroup $group */
        $group = $builder->getData();
        if (!$group instanceof AbstractAttributeGroup) {
            throw new \Exception(sprintf(
                'Attribute group should declared before creating the form and should extends %s',
                AbstractAttributeGroup::class
            ));
        }

        $groupClass = get_class($group);

        $builder
            ->add('groupKey', null, [
                'label' => 'form.attribute_group.group_key.label',
                'disabled' => null !== $group->getId(),
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => $this->translationLocaleProvider->hasMultipleLocalesCodes() ? 'form.attribute_group.translations.label' : false,
                'entry_type' => TranslationType::class,
                'entry_options' => [
                    'data_class' => $this->getTargetEntityClass($groupClass, 'translations'),
                ],
            ])
            ->add('attributes', CollectionType::class, [
                'label' => 'form.attribute_group.attributes.label',
                'entry_type' => OrderedAttributeType::class,
                'entry_options' => [
                    'data_class' => $this->getTargetEntityClass($groupClass, 'attributes'),
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);

        if ($group instanceof AbstractAttributeGroupWithSubgroups) {
            $builder->add('childGroups', CollectionType::class, [
                'label' => 'form.attribute_group.child_groups.label',
                'entry_type' => OrderedGroupType::class,
                'entry_options' => [
                    'data_class' => $this->getTargetEntityClass($groupClass, 'childGroups'),
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'form.attribute_group.save.label',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbstractAttributeGroup::class,
            'translation_domain' => 'elasticform',
        ]);
    }
}
