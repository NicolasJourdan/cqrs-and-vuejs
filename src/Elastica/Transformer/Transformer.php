<?php

namespace App\Elastica\Transformer;

use App\Builder\ProductBuilder;
use App\Model\Product;
use Elastica\Exception\NotImplementedException;
use Elastica\Result;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;

/**
 * Class Transformer
 *
 * @package App\Elastica\Transformer
 */
class Transformer implements ElasticaToModelTransformerInterface
{
    /** @var ProductBuilder */
    private ProductBuilder $productBuilder;

    /**
     * Transformer constructor.
     * @param ProductBuilder $productBuilder
     */
    public function __construct(ProductBuilder $productBuilder)
    {
        $this->productBuilder = $productBuilder;
    }

    public function transform(array $elasticaObjects)
    {
        $products = [];

        /** @var Result $elasticaObject */
        foreach ($elasticaObjects as $elasticaObject) {
            $products[] = $this->productBuilder->build($elasticaObject->getData());
        }

        return $products;
    }

    public function hybridTransform(array $elasticaObjects)
    {
        throw new NotImplementedException();
    }

    public function getObjectClass()
    {
        return Product::class;
    }

    public function getIdentifierField()
    {
        return 'id';
    }
}
