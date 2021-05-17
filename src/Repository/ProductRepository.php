<?php

namespace App\Repository;

use App\Builder\ProductBuilder;
use App\Entity\Product;
use App\Model\Product as ProductDto;
use App\Elastica\Repository\ProductRepository as ProductElasticaRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /** @var ProductElasticaRepository */
    private $elasticaRepository;

    /** @var ProductBuilder */
    private $productBuilder;

    /**
     * ProductRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param ProductBuilder $productBuilder
     * @param ProductElasticaRepository $elasticaRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        ProductBuilder $productBuilder,
        ProductElasticaRepository $elasticaRepository
    ) {
        parent::__construct($registry, Product::class);
        $this->productBuilder = $productBuilder;
        $this->elasticaRepository = $elasticaRepository;
    }

    /**
     * @return ProductDto[]
     */
    public function getAll(): array
    {
        try {
            return $this->elasticaRepository->getAll();
        } catch (Exception $exception) {
            return $this->productBuilder->buildFromEntities($this->getAllEntities());
        }
    }

    /**
     * @return array
     */
    public function getAllEntities(): array
    {
        return $this->findAll();
    }

    /**
     * @param int $id
     *
     * @return ProductDto|null
     */
    public function getOne(int $id): ?ProductDto
    {
        try {
            return $this->elasticaRepository->getOne($id);
        } catch (Exception $exception) {
            return $this->productBuilder->buildFromEntity($this->find($id));
        }
    }

    /**
     * @param Product $product
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    /**
     * @param Product $product
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }
}
