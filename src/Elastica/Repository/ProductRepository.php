<?php

namespace App\Elastica\Repository;

use App\Model\Product;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

/**
 * Class ProductRepository
 * @package App\Elastica\Repository
 */
class ProductRepository
{
    /** @var TransformedFinder */
    private $finder;

    /**
     * ProductRepository constructor.
     * @param TransformedFinder $finder
     */
    public function __construct(TransformedFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->finder->find('');
    }

    /**
     * @param int $id
     *
     * @return Product|null
     */
    public function getOne(int $id): ?Product
    {
        $boolQuery = new BoolQuery();

        $term = new Term();
        $term->setTerm('id', $id);

        $boolQuery->addMust($term);

        $result = $this->finder->find($boolQuery);

        return empty($result) ? null : $result[0];
    }
}
