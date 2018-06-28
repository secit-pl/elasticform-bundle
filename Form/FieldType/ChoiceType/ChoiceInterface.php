<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Form\FieldType\ChoiceType;

/**
 * Interface ChoiceInterface.
 *
 * @author Tomasz Gemza
 */
interface ChoiceInterface
{
    /**
     * Get form choice label.
     *
     * @return string
     */
    public function getFormChoiceLabel(): string;

    /**
     * Get form choice value.
     *
     * @return null|string|mixed
     */
    public function getFormChoiceValue();
}
