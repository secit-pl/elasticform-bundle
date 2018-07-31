<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm;

use SecIT\ElasticFormBundle\ElasticForm\FieldType\TypeInterface;
use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Entity\AbstractAttributeGroup;
use SecIT\ElasticFormBundle\Entity\AbstractAttributeGroupWithSubgroups;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Factory.
 *
 * @author Tomasz Gemza
 */
class FormFactory
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var array|TypeInterface[]
     */
    private $fieldTypes = [];

    /**
     * FormFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RegistryInterface    $doctrine
     */
    public function __construct(FormFactoryInterface $formFactory, RegistryInterface $doctrine)
    {
        $this->formFactory = $formFactory;
        $this->doctrine = $doctrine;
    }

    /**
     * Get defined field types.
     *
     * @return array|TypeInterface[]
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * Get defined field types names.
     *
     * @return array|string[]
     */
    public function getFieldTypesNames(): array
    {
        return array_keys($this->fieldTypes);
    }

    /**
     * Add field type.
     *
     * @param TypeInterface $type
     *
     * @return FormFactory
     */
    public function addFiledType(TypeInterface $type): self
    {
        $this->fieldTypes[$type->getName()] = $type;

        return $this;
    }

    /**
     * Get field type.
     *
     * @param string $name
     *
     * @return TypeInterface
     */
    public function getFieldType(string $name): TypeInterface
    {
        if (!array_key_exists($name, $this->fieldTypes)) {
            throw new \LogicException(sprintf(
                'Invalid attribute type (%s). Allowed types are %s.',
                $name,
                implode(', ', array_keys($this->getFieldTypesNames()))
            ));
        }

        return$this->fieldTypes[$name];
    }

    /**
     * Create attribute group form builder.
     *
     * @param AbstractAttributeGroup $group
     *
     * @return FormBuilderInterface
     */
    public function createAttributeGroupFormBuilder(AbstractAttributeGroup $group): FormBuilderInterface
    {
        $form = $this->formFactory->createNamedBuilder($group->getGroupKey(), FormType::class, null, [
            'label' => $group->getName(),
            'inherit_data' => true,
            'translation_domain' => false,
        ]);

        if ($group instanceof AbstractAttributeGroupWithSubgroups) {
            foreach ($group->getChildGroups() as $childGroup) {
                $form->add($this->createAttributeGroupFormBuilder($childGroup->getChildGroup()));
            }
        }

        foreach ($group->getAttributes() as $attribute) {
            $form->add($this->createAttributeFormBuilder($attribute->getAttribute()));
        }

        return $form;
    }

    /**
     * Create attribute form builder.
     *
     * @param AbstractAttribute $attribute
     *
     * @return FormBuilderInterface
     *
     * @throws \LogicException
     */
    public function createAttributeFormBuilder(AbstractAttribute $attribute): FormBuilderInterface
    {
        $options = $attribute->getOptions();
        $options['name'] = $attribute->getAttributeKey();
        $options['label'] = $attribute->getName();

        if (!isset($options['constraints'])) {
            $options['constraints'] = [];
        }

        if ($attribute->isRequired()) {
            $options['required'] = true;
            $options['constraints'][] = new NotBlank();
        }

        return $this->getFieldType($attribute->getType())
            ->getFormBuilder($options);
    }

    /**
     * Create attribute form builder.
     *
     * @param AbstractAttribute $attribute
     * @param array             $options
     *
     * @return FormBuilderInterface
     *
     * @throws \LogicException
     */
    public function createAttributeConfigurationFormBuilder(AbstractAttribute $attribute, array $options = []): FormBuilderInterface
    {
        return $this->getFieldType($attribute->getType())
            ->getConfigurationFormBuilder($attribute, $options);
    }
}
