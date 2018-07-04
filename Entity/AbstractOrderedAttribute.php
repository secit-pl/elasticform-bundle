<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractOrderedAttribute.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractOrderedAttribute
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
     * @var null|AbstractAttributeGroup
     *
     * ORM\ManyToOne(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractAttributeGroup", inversedBy="attributes")
     */
    protected $group;

    /**
     * @var null|AbstractAttribute
     *
     * ORM\ManyToOne(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractAttribute", inversedBy="orderedAttributeGroups", cascade={"persist"})
     */
    protected $attribute;

    /**
     * @var null|int
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Range(min="0", max="999999")
     */
    protected $position;

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
     * Get group.
     *
     * @return null|AbstractAttributeGroup
     */
    public function getGroup(): ?AbstractAttributeGroup
    {
        return $this->group;
    }

    /**
     * Set group.
     *
     * @param AbstractAttributeGroup $group
     *
     * @return AbstractOrderedAttribute
     */
    public function setGroup(AbstractAttributeGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return null|AbstractAttribute
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
     * @return AbstractOrderedAttribute
     */
    public function setAttribute(AbstractAttribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param int $position
     *
     * @return AbstractOrderedAttribute
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
