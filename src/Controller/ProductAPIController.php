<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Rest\Route("/api", name="products_")
 */
class ProductAPIController extends AbstractController
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * ProductAPIController constructor.
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/products", name="get_all")
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $products = $this->productRepository->getAll();
        $data = $this->serializer->serialize($products, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Get("/products/{id}", name="get_one")
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $product = $this->productRepository->getOne($id);

        if (null === $product) {
            throw new NotFoundHttpException('No product found with id ' . $id);
        }

        $data = $this->serializer->serialize(
            $product,
            JsonEncoder::FORMAT
        );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Post("/products", name="create")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $name = $content['name'] ?? null;
        $price = $content['price'] ?? null;

        if (!$name || null === $price) {
            throw new BadRequestHttpException('Check your body');
        }

        $product = (new Product())
            ->setName($name)
            ->setPrice($price)
        ;

        $this->productRepository->save($product);

        $data = $this->serializer->serialize($product, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Rest\Put("/products/{id}", name="edit")
     *
     * @param Product $product
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function edit(Product $product, Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $name = $content['name'] ?? null;
        $price = $content['price'] ?? null;

        if ($name) {
            $product->setName($name);
        }

        if (null !== $price) {
            $product->setPrice($price);
        }

        $this->productRepository->save($product);

        $data = $this->serializer->serialize($product, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Delete("/products/{id}", name="delete")
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function delete(Product $product): JsonResponse
    {

        $this->productRepository->remove($product);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT, []);
    }

    /**
     * @Rest\Get("/products-average-price", name="average_price")
     *
     * @return JsonResponse
     */
    public function averagePrice(): JsonResponse
    {
        $products = $this->productRepository->getAll();

        if (!$products) {
            return new JsonResponse(['averagePrice' => 0], Response::HTTP_OK);
        }

        $amountTotalPrice = 0;

        foreach ($products as $product) {
            $amountTotalPrice += $product->getPrice();
        }

        return new JsonResponse(
            ['averagePrice' => round($amountTotalPrice/count($products), 2)],
            Response::HTTP_OK
        );
    }
}
