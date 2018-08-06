<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

/**
 * Interface TypeInterface.
 *
 * @author Tomasz Gemza
 */
interface DataTransformerInterface
{
    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * @param mixed $value   The value in the original representation
     * @param array $options The field options
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value, array $options = []);

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * @param mixed $value   The value in the transformed representation
     * @param array $options The field options
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($value, array $options = []);
}
