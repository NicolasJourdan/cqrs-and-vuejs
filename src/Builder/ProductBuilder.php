<?php

namespace App\Builder;

use App\Model\Product;
use App\Entity\Product as ProductEntity;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductBuilder
 *
 * @package App\Builder
 */
class ProductBuilder
{
    /** @var OptionsResolver */
    private OptionsResolver $resolver;

    /**
     * ProductBuilder constructor.
     */
    public function __construct()
    {
        $this->resolver = new OptionsResolver();
        $this->configureOptions($this->resolver);
    }

    /**
     * @param array $data
     *
     * @return Product
     */
    public function build(array $data): Product
    {
        $data = $this->resolver->resolve($data);

        return new Product($data['id'], $data['name'], $data['price']);
    }

    /**
     * @param array $products
     *
     * @return array
     */
    public function buildFromEntities(array $products): array
    {
        $productsDto = [];

        /** @var ProductEntity $product */
        foreach ($products as $product) {
            $productsDto[] = $this->buildFromEntity($product);
        }

        return $productsDto;
    }

    /**
     * @param ProductEntity|null $product
     *
     * @return Product|null
     */
    public function buildFromEntity(?ProductEntity $product): ?Product
    {
        if (null === $product) {
            return null;
        }

        return new Product(
            $product->getId(),
            $product->getName(),
            $product->getPrice()
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['id', 'name', 'price'])
            ->setAllowedTypes('id', 'int')
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('price', ['float', 'int'])
        ;
    }
}
