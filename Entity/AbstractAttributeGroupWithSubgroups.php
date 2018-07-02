<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use SecIT\EntityTranslationBundle\Translations\TranslatableInterface;
use SecIT\EntityTranslationBundle\Translations\TranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractAttributeGroupWithSubgroups.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractAttributeGroupWithSubgroups extends AbstractAttributeGroup
{
    /**
     * @var Collection|AbstractOrderedAttributeGroup[]
     *
     * ORM\OneToMany(targetEntity="Database\Entity\Attribute\AttributeGroup\OrderedGroup", mappedBy="childGroup", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $parentGroups;

    /**
     * @var Collection|AbstractOrderedAttributeGroup[]
     *
     * ORM\OneToMany(targetEntity="Database\Entity\Attribute\AttributeGroup\OrderedGroup", mappedBy="parentGroup", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $childGroups;

    /**
     * AbstractAttributeGroupWithSubgroups constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->childGroups = new ArrayCollection();
        $this->parentGroups = new ArrayCollection();
    }

    /**
     * Get parent groups.
     *
     * @return AbstractOrderedAttributeGroup[]|Collection
     */
    public function getParentGroups(): Collection
    {
        return $this->parentGroups;
    }

    /**
     * Set parent groups.
     *
     * @param AbstractOrderedAttributeGroup[]|Collection $orderedGroups
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function setParentGroups(Collection $orderedGroups): self
    {
        foreach ($orderedGroups as $orderedGroup) {
            $orderedGroup->setChildGroup($this);
        }

        $this->parentGroups = $orderedGroups;

        return $this;
    }

    /**
     * Add parent group.
     *
     * @param AbstractOrderedAttributeGroup $orderedGroup
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function addParentGroup(AbstractOrderedAttributeGroup $orderedGroup): self
    {
        $orderedGroup->setChildGroup($this);
        if (!$this->getParentGroups()->contains($orderedGroup)) {
            $this->getParentGroups()->add($orderedGroup);
        }

        return $this;
    }

    /**
     * Remove parent group.
     *
     * @param OrderedAttributeGroup $orderedGroup
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function removeParentGroup(AbstractOrderedAttributeGroup $orderedGroup): self
    {
        if ($this->getParentGroups()->contains($orderedGroup)) {
            $this->getParentGroups()->removeElement($orderedGroup);
        }

        return $this;
    }

    /**
     * Get child groups.
     *
     * @return AbstractOrderedAttributeGroup[]|Collection
     */
    public function getChildGroups(): Collection
    {
        return $this->childGroups;
    }

    /**
     * Set child groups.
     *
     * @param AbstractOrderedAttributeGroup[]|Collection $orderedGroups
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function setChildGroups(Collection $orderedGroups): self
    {
        foreach ($orderedGroups as $orderedGroup) {
            $orderedGroup->setParentGroup($this);
        }

        $this->childGroups = $orderedGroups;

        return $this;
    }

    /**
     * Add child group.
     *
     * @param OrderedAttributeGroup $orderedGroup
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function addChildGroup(AbstractOrderedAttributeGroup $orderedGroup): self
    {
        $orderedGroup->setParentGroup($this);
        if (!$this->getChildGroups()->contains($orderedGroup)) {
            $this->getChildGroups()->add($orderedGroup);
        }

        return $this;
    }

    /**
     * Remove child group.
     *
     * @param AbstractOrderedAttributeGroup $orderedGroup
     *
     * @return AbstractAttributeGroupWithSubgroups
     */
    public function removeChildGroup(AbstractOrderedAttributeGroup $orderedGroup): self
    {
        if ($this->getChildGroups()->contains($orderedGroup)) {
            $this->getChildGroups()->removeElement($orderedGroup);
        }

        return $this;
    }

    /**
     * Get attribute by key.
     *
     * @param string $attributeKey
     *
     * @return AbstractAttribute
     *
     * @throws \Exception
     */
    public function getAttributeByKey(string $attributeKey): AbstractAttribute
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getAttribute()->getAttributeKey() === $attributeKey) {
                return $attribute->getAttribute();
            }
        }

        foreach ($this->getChildGroups() as $childGroup) {
            try {
                return $childGroup->getChildGroup()->getAttributeByKey($attributeKey, $searchInChildGroups);
            } catch (\Exception $e) {
            }
        }

        throw new \Exception(
            sprintf('AttributeGroup "%s" does not have attribute with key "%s".', $this->getName(), $attributeKey)
        );
    }
}
