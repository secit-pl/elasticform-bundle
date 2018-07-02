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
 * Class AbstractAttributeGroup.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 *
 * @method AbstractAttributeGroupTranslation getTranslation(?string $locale)
 * @method Collection|AbstractAttributeGroupTranslation[] getTranslations
 */
abstract class AbstractAttributeGroup implements TranslatableInterface
{
    use TranslatableTrait  {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var null|int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     * @Assert\Type("alnum")
     * @Assert\Type("lower")
     */
    protected $groupKey;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     */
    protected $name;

    /**
     * @var Collection|AbstractOrderedAttribute[]
     *
     * ORM\OneToMany(targetEntity="Database\Entity\Attribute\AttributeGroup\OrderedAttribute", mappedBy="group", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid()
     */
    protected $attributes;

    /**
     * AbstractAttributeGroup constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->attributes = new ArrayCollection();
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

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
     * Get key.
     *
     * @return null|string
     */
    public function getGroupKey(): ?string
    {
        return $this->groupKey;
    }

    /**
     * Set key.
     *
     * @param string $groupKey
     *
     * @return AbstractAttributeGroup
     */
    public function setGroupKey(string $groupKey): self
    {
        $this->groupKey = $groupKey;

        return $this;
    }

    /**
     * Get name.
     *
     * @param null|string locale
     *
     * @return null|string
     */
    public function getName(?string $locale = null): ?string
    {
        return $this->getTranslation($locale)->getName();
    }

    /**
     * Get attributes.
     *
     * @return AbstractOrderedAttribute[]|Collection
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * Set attributes.
     *
     * @param AbstractOrderedAttribute[]|Collection $orderedAttributes
     *
     * @return AbstractAttributeGroup
     */
    public function setAttributes(Collection $orderedAttributes): self
    {
        foreach ($orderedAttributes as $orderedAttribute) {
            $orderedAttribute->setGroup($this);
        }

        $this->attributes = $orderedAttributes;

        return $this;
    }

    /**
     * Add attribute.
     *
     * @param AbstractOrderedAttribute $orderedAttribute
     *
     * @return AbstractAttributeGroup
     */
    public function addAttribute(AbstractOrderedAttribute $orderedAttribute): self
    {
        $orderedAttribute->setGroup($this);
        if (!$this->getAttributes()->contains($orderedAttribute)) {
            $this->getAttributes()->add($orderedAttribute);
        }

        return $this;
    }

    /**
     * Remove attribute.
     *
     * @param AbstractOrderedAttribute $orderedAttribute
     *
     * @return AbstractAttributeGroup
     */
    public function removeAttribute(AbstractOrderedAttribute $orderedAttribute): self
    {
        if ($this->getAttributes()->contains($orderedAttribute)) {
            $this->getAttributes()->removeElement($orderedAttribute);
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

        throw new \Exception(
            sprintf('AttributeGroup "%s" does not have attribute with key "%s".', $this->getName(), $attributeKey)
        );
    }
}
