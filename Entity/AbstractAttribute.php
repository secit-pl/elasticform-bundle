<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use SecIT\EntityTranslationBundle\Translations\TranslatableInterface;
use SecIT\EntityTranslationBundle\Translations\TranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SecIT\ElasticFormBundle\ElasticForm\FieldType\TypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class AbstractAttribute.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 *
 * @UniqueEntity("attributeKey")
 *
 * @method AbstractAttributeTranslation getTranslation(?string $locale)
 * @method Collection|AbstractAttributeTranslation[] getTranslations
 */
abstract class AbstractAttribute implements TranslatableInterface
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
     * @ORM\Column(type="string", length=128)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 128)
     * @Assert\Type("lower")
     */
    protected $attributeKey;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     */
    protected $type;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Assert\NotNull()
     */
    protected $required = false;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $options = [];

    /**
     * @var Collection|AbstractOrderedAttributeGroup[]
     *
     * ORM\OneToMany(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractOrderedAttributeGroup", mappedBy="attribute", cascade={"all"})
     */
    protected $orderedAttributeGroups;

    /**
     * @var Collection|AbstractAttributeValue[]
     *
     * ORM\OneToMany(targetEntity="SecIT\ElasticFormBundle\Entity\AbstractAttributeValue", mappedBy="attribute", cascade={"all"})
     */
    protected $values;

    /**
     * Get values.
     *
     * @return Collection|AbstractAttributeValue[]
     */
    public function getProductsValues(): Collection
    {
        return $this->values;
    }

    /**
     * Attribute constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->orderedAttributeGroups = new ArrayCollection();
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
    public function getAttributeKey(): ?string
    {
        return $this->attributeKey;
    }

    /**
     * Set key.
     *
     * @param string $attributeKey
     *
     * @return AbstractAttribute
     */
    public function setAttributeKey(string $attributeKey): self
    {
        $this->attributeKey = $attributeKey;

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
     * Get type.
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string|TypeInterface $type
     *
     * @return AbstractAttribute
     */
    public function setType($type): self
    {
        if ($type instanceof TypeInterface) {
            $this->type = $type->getName();
        } else {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * Is required?
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set required.
     *
     * @param bool $required
     *
     * @return AbstractAttribute
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return AbstractAttribute
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get ordered attribute groups.
     *
     * @return AbstractOrderedAttributeGroup[]|Collection
     */
    public function getOrderedAttributeGroups()
    {
        return $this->orderedAttributeGroups;
    }

    /**
     * Set ordered attribute groups.
     *
     * @param AbstractOrderedAttributeGroup[]|Collection $orderedAttributeGroups
     *
     * @return AbstractAttribute
     */
    public function setOrderedAttributeGroups(Collection $orderedAttributeGroups): self
    {
        foreach ($orderedAttributeGroups as $attributeGroup) {
            $attributeGroup->setAttribute($this);
        }

        $this->orderedAttributeGroups = $orderedAttributeGroups;

        return $this;
    }
}
