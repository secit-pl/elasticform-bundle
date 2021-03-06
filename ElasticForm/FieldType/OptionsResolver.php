<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Class StringType.
 *
 * @author Tomasz Gemza
 *
 * @todo: default value
 * @todo: input hint (symfony 4.1)
 */
class OptionsResolver extends SymfonyOptionsResolver
{
    /**
     * OptionsResolver constructor.
     */
    public function __construct()
    {
        $this->setDefaults([
            'name' => null,
            'label' => null,
            'label_attr' => [],
            'required' => false,
            'attr' => [],
            'constraints' => [],
            'translation_domain' => null,
        ]);

        $this->setAllowedTypes('name', 'string');
        $this->setAllowedTypes('label', 'string');
        $this->setAllowedTypes('label_attr', 'array');
        $this->setAllowedTypes('required', 'bool');
        $this->setAllowedTypes('attr', 'array');
        $this->setAllowedTypes('constraints', 'array');
        $this->setAllowedTypes('translation_domain', ['null', 'string']);

        $this->setRequired('name');
        $this->setRequired('label');
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(array $options = [])
    {
        // ignore extra options
        $options = array_intersect_key($options, array_flip($this->getDefinedOptions()));

        $data = parent::resolve($options);

        if ($data['required']) {
            $data['constraints'][] = new Constraints\NotBlank();
        }

        return $data;
    }
}
