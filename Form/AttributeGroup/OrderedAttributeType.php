<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\AttributeGroup;

use SecIT\ElasticFormBundle\Entity\AbstractOrderedAttribute;
use SecIT\ElasticFormBundle\Helper\EntityMetadataTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrderedAttributeType.
 *
 * @author Tomasz Gemza
 */
class OrderedAttributeType extends AbstractType
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
        $builder
            ->add('attribute', EntityType::class, [
                'label' => 'form.attribute_group.attributes.fields.attribute.label',
                'class' => $this->getTargetEntityClass($options['data_class'], 'attribute'),
            ])
            ->add('position', null, [
                'label' => 'form.attribute_group.attributes.fields.position.label',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbstractOrderedAttribute::class,
            'translation_domain' => 'elasticform',
        ]);
    }
}
