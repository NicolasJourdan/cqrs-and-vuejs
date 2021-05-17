<?php

namespace App\Elastica\Provider;

use App\Repository\ProductRepository;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Class ProductProvider
 * @package App\Elastica\Provider
 */
class ProductProvider implements PagerProviderInterface
{
    /** @var ProductRepository */
    private $productRepository;

    /**
     * ProductProvider constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /** {@inheritDoc} */
    public function provide(array $options = array())
    {
        $products = $this->productRepository->getAllEntities();

        return new PagerfantaPager(new Pagerfanta(new ArrayAdapter($products)));
    }
}
