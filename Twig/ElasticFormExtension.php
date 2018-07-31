<?php

declare(strict_types=1);

namespace SecIT\ElasticFormBundle\Twig;

use SecIT\ElasticFormBundle\Helper\ValueHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ElasticFormExtension.
 *
 * @author Tomasz Gemza
 */
class ElasticFormExtension extends AbstractExtension
{
    /**
     * @var ValueHelper
     */
    protected $valueHelper;

    /**
     * ElasticFormExtension constructor.
     *
     * @param ValueHelper $valueHelper
     */
    public function __construct(ValueHelper $valueHelper)
    {
        $this->valueHelper = $valueHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('elasticform_attribute_value', [$this->valueHelper, 'getValueAsString']),
        ];
    }
}
