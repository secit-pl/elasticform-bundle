<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Doctrine\Common\Inflector\Inflector;
use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Form\AttributeConfigurationType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AbstractType.
 *
 * @author Tomasz Gemza
 */
abstract class AbstractType implements TypeInterface
{
    /**
     * @var OptionsResolver
     */
    protected $optionsResolver;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * AbstractType constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TranslatorInterface  $translator
     */
    public function __construct(FormFactoryInterface $formFactory, TranslatorInterface $translator)
    {
        $this->optionsResolver = new OptionsResolver();
        $this->formFactory = $formFactory;
        $this->translator = $translator;

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * Helper method used to configure additional options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        $name = (new \ReflectionClass($this))->getShortName();
        $name = Inflector::tableize(substr($name, 0, -4));

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value, array $options = [])
    {
        if (null === $value) {
            return '';
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value, array $options = [])
    {
        if ('' === $value) {
            return null;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function valueToString(AbstractAttribute $attribute, $value): string
    {
        if (null === $value) {
            return '';
        } elseif ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        } elseif (is_array($value)) {
            $return = [];
            foreach ($value as $val) {
                $return[] = $this->valueToString($val);
            }

            return implode(', ', $return);
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(AttributeConfigurationType::class, $data, $options);
    }

    /**
     * Create form builder.
     *
     * @param string $type
     * @param array  $options
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilder(string $type, array $options): FormBuilderInterface
    {
        $name = $options['name'];
        unset($options['name']);

        return $this->formFactory
            ->createNamedBuilder($name, $type, null, $options)
            ->addModelTransformer($this->getModelTransformer($options));
    }

    /**
     * Get model transformer.
     *
     * @param array $options
     *
     * @return DataTransformerInterface
     */
    protected function getModelTransformer(array $options = []): DataTransformerInterface
    {
        return new CallbackTransformer(
            function ($value) use ($options) {
                return $this->transform($value, $options);
            },
            function ($value) use ($options) {
                return $this->reverseTransform($value, $options);
            }
        );
    }
}
