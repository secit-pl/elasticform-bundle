<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractOrderedAttributeGroup.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractOrderedAttributeGroup
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
     * ORM\ManyToOne(targetEntity="Database\Entity\AttributeGroup", inversedBy="childGroups", cascade={"persist"})
     */
    protected $parentGroup;

    /**
     * @var null|AbstractAttributeGroup
     *
     * ORM\ManyToOne(targetEntity="Database\Entity\AttributeGroup", inversedBy="parentGroups", cascade={"persist"})
     */
    protected $childGroup;

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
    public function getParentGroup(): ?AbstractAttributeGroup
    {
        return $this->parentGroup;
    }

    /**
     * Set group.
     *
     * @param AbstractAttributeGroup $parentGroup
     *
     * @return OrderedGroup
     */
    public function setParentGroup(AbstractAttributeGroup $parentGroup): self
    {
        $this->parentGroup = $parentGroup;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return null|AttributeGroup
     */
    public function getChildGroup(): ?AttributeGroup
    {
        return $this->childGroup;
    }

    /**
     * Set attribute.
     *
     * @param AbstractAttributeGroup $childGroup
     *
     * @return OrderedGroup
     */
    public function setChildGroup(AbstractAttributeGroup $childGroup): self
    {
        $this->childGroup = $childGroup;

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
     * @return OrderedGroup
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
