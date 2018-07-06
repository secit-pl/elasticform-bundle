<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AbstractAttributeValue entity.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractAttributeValue
{
    /**
     * @var null|int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var null|AbstractElasticEntity
     *
     * ORM\ManyToOne(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractElasticEntity", inversedBy="attributesValues", cascade={"persist"})
     */
    protected $elasticEntity;

    /**
     * @var null|AbstractAttribute
     *
     * ORM\ManyToOne(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractAttribute", inversedBy="values", cascade={"persist"})
     */
    protected $attribute;

    /**
     * Redundant attribute key value used in product attributes collection array key naming.
     *
     * @var null|string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $attributeKey;

    /**
     * @var null|string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     */
    protected $value;

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get elastic entity.
     *
     * @return null|AbstractElasticEntity
     */
    public function getElasticEntity(): ?AbstractElasticEntity
    {
        return $this->elasticEntity;
    }

    /**
     * Set elastic entity.
     *
     * @param null|AbstractElasticEntity $elasticEntity
     *
     * @return AbstractAttributeValue
     */
    public function setElasticEntity(?AbstractElasticEntity $elasticEntity): self
    {
        $this->elasticEntity = $elasticEntity;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return AbstractAttribute|null
     */
    public function getAttribute(): ?AbstractAttribute
    {
        return $this->attribute;
    }

    /**
     * Set attribute.
     *
     * @param AbstractAttribute $attribute
     *
     * @return AbstractAttributeValue
     */
    public function setAttribute(AbstractAttribute $attribute): self
    {
        $this->attribute = $attribute;
        $this->attributeKey = $attribute->getAttributeKey();

        return $this;
    }

    /**
     * Get attribute key.
     *
     * @return null|string
     */
    public function getAttributeKey(): ?string
    {
        return $this->attributeKey;
    }

    /**
     * Get value.
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set value.
     *
     * @param mixed $value
     *
     * @return AbstractAttributeValue
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}
