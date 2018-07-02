<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Entity;

use SecIT\EntityTranslationBundle\Entity\AbstractTranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractAttributeTranslation.
 *
 * @author Tomasz Gemza
 *
 * @ORM\MappedSuperclass()
 *
 * @method AbstractAttribute getTranslatable
 */
abstract class AbstractAttributeTranslation extends AbstractTranslation
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
     * @var null|string
     *
     * @ORM\Column(type="string", length=256)
     */
    protected $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param null|string $name
     *
     * @return AbstractAttributeTranslation
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
