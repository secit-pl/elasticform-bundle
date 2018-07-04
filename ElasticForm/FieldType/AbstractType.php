<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\ElasticForm\FieldType;

use Doctrine\Common\Util\Inflector;
use SecIT\ElasticFormBundle\Entity\AbstractAttribute;
use SecIT\ElasticFormBundle\Form\AttributeConfigurationType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * AbstractType constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->optionsResolver = new OptionsResolver();
        $this->formFactory = $formFactory;

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
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ('' === $value) {
            return null;
        }

        return $value;
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
            ->addModelTransformer(new CallbackTransformer(
                [$this, 'transform'],
                [$this, 'reverseTransform']
            ));
    }
}
