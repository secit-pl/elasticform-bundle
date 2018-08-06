<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Helper;

use SecIT\ElasticFormBundle\ElasticForm\FormFactory;
use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Entity\AbstractElasticEntity;

/**
 * Class ValueHelper.
 *
 * @author Tomasz Gemza
 */
class ValueHelper
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * ValueHelper constructor.
     *
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Get transformed attribute value from elastic entity.
     *
     * @param AbstractElasticEntity $elasticEntity
     * @param AbstractAttribute     $attribute
     *
     * @return mixed
     */
    public function getTransformedValue(AbstractElasticEntity $elasticEntity, AbstractAttribute $attribute)
    {
        return $this->formFactory
            ->getFieldType($attribute->getType())
            ->transform(
                $elasticEntity->getAttributeStrictTypeValue($attribute->getAttributeKey()),
                $attribute->getOptions()
            );
    }

    /**
     * Get value as string.
     *
     * @param AbstractElasticEntity $elasticEntity
     * @param AbstractAttribute $attribute
     *
     * @return string
     */
    public function getValueAsString(AbstractElasticEntity $elasticEntity, AbstractAttribute $attribute): string
    {
        $fieldType = $this->formFactory->getFieldType($attribute->getType());
        $value = $fieldType->transform(
            $elasticEntity->getAttributeStrictTypeValue($attribute->getAttributeKey()),
            $attribute->getOptions()
        );

        return $fieldType->valueToString($attribute, $value);
    }
}
