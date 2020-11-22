<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Rest\Route("/api", name="products_")
 */
class ProductController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/products", name="get_all")
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $data = $this->serializer->serialize($products, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Get("/products/{id}", name="get_one")
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function getOne(Product $product): JsonResponse
    {
        $data = $this->serializer->serialize($product, JsonEncoder::FORMAT);

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

        $this->em->persist($product);
        $this->em->flush();

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

        $this->em->flush();

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

        $this->em->remove($product);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT, []);
    }
}
