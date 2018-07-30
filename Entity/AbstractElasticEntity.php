<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractElasticEntity.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractElasticEntity
{
    /**
     * @var Collection|AbstractAttributeValue[]
     *
     * ORM\OneToMany(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractAttributeValue", mappedBy="elasticEntity", cascade={"persist"}, orphanRemoval=true)
     */
    protected $attributesValues;

    /**
     * AbstractElasticEntity constructor.
     */
    public function __construct()
    {
        $this->attributesValues = new ArrayCollection();
    }

    /**
     * Get attribute value class.
     *
     * @return string
     */
    abstract public function getAttributeValueClass(): string;

    /**
     * Get attribute value class.
     *
     * @param string $key
     *
     * @return AbstractAttribute
     */
    abstract public function getAttributeByKey(string $key): AbstractAttribute;

    /**
     * Get dynamic attribute value.
     *
     * @param string $attributeKey
     *
     * @return mixed
     */
    public function __get($attributeKey)
    {
        return $this->getAttributeStrictTypeValue($attributeKey);
    }

    /**
     * Set dynamic attribute value.
     *
     * @param string $attributeKey
     * @param mixed  $value
     *
     * @return AbstractElasticEntity
     *
     * @throws \Exception
     */
    public function __set($attributeKey, $value)
    {
        return $this->setAttributeStrictTypeValue($attributeKey, $value);
    }

    /**
     * Get attributes values.
     *
     * @return Collection|AbstractAttributeValue[]
     */
    public function getAttributesValues()
    {
        return $this->attributesValues;
    }

    /**
     * Set attributes values.
     *
     * @param Collection|AbstractAttributeValue[] $attributesValues
     *
     * @return AbstractElasticEntity
     */
    public function setAttributesValues(Collection $attributesValues): self
    {
        foreach ($attributesValues as $attributesValue) {
            $attributesValue->setElasticEntity($this);
        }

        $this->attributesValues = $attributesValues;

        return $this;
    }

    /**
     * Set attributes values.
     *
     * @param AbstractAttributeValue $attributeValue
     *
     * @return AbstractElasticEntity
     */
    public function addAttributeValue(AbstractAttributeValue $attributeValue): self
    {
        $attributeValue->setElasticEntity($this);
        $this->attributesValues->add($attributeValue);

        return $this;
    }

    /**
     * Remove attribute values.
     *
     * @param string $attributeKey
     *
     * @return AbstractElasticEntity
     */
    public function removeAttributeValuesByKey(string $attributeKey): self
    {
        foreach ($this->getAttributesValues() as $attributeValue) {
            if ($attributeValue->getAttributeKey() === $attributeKey) {
                $this->getAttributesValues()->removeElement($attributeValue);
            }
        }

        return $this;
    }

    /**
     * Get attribute strict type value.
     *
     * @param string $attributeKey
     *
     * @return array|mixed|null
     */
    public function getAttributeStrictTypeValue(string $attributeKey)
    {
        $values = [];
        foreach ($this->getAttributesValues() as $attributeValue) {
            if ($attributeValue->getAttributeKey() === $attributeKey) {
                $values[] = $attributeValue->getValue();
            }
        }

        $valuesCount = count($values);
        if (1 === $valuesCount) {
            return $values[0];
        } elseif ($valuesCount > 1) {
            return $values;
        }

        return null;
    }

    /**
     * Set attribute strict type value.
     *
     * @param string $attributeKey
     * @param mixed  $values
     *
     * @return AbstractElasticEntity
     *
     * @throws \Exception
     *
     * @todo: Try to update existing elements instead of removing all and create a new ones.
     */
    public function setAttributeStrictTypeValue(string $attributeKey, $values): self
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        $this->removeAttributeValuesByKey($attributeKey);
        foreach ($values as $value) {
            if (null === $value) {
                continue;
            }

            $class = $this->getAttributeValueClass();
            /** @var AbstractAttributeValue $attributeValue */
            $attributeValue = new $class();
            if (!$attributeValue instanceof AbstractAttributeValue) {
                throw new \ErrorException(sprintf(
                    'Attribute value class (%s) should extends %s',
                    $class,
                    AbstractAttributeValue::class
                ));
            }

            $attributeValue->setAttribute($this->getAttributeByKey($attributeKey))
                ->setValue($value);

            $this->addAttributeValue($attributeValue);
        }

        return $this;
    }
}
