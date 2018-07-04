<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Helper;

use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Trait EntityMetadataTrait.
 *
 * @author Tomasz Gemza
 */
trait EntityMetadataTrait
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * Get target entity class name.
     *
     * @param string $class
     * @param string $field
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getTargetEntityClass(string $class, string $field): string
    {
        $associationMappings = $this->doctrine
            ->getManager()
            ->getClassMetadata($class)
            ->associationMappings;

        if (!array_key_exists($field, $associationMappings)) {
            throw new \Exception(sprintf('Entity `%s` field `%s` mapping not found.', $class, $field));
        }

        return $associationMappings[$field]['targetEntity'];
    }
}
