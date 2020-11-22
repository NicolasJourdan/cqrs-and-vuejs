<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Rest\Route("/api", name="carts_")
 */
class CartController extends AbstractController
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
     * @Rest\Get("/carts", name="get_all")
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $carts = $this->em->getRepository(Cart::class)->findAll();
        $data = $this->serializer->serialize($carts, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Get("/carts/{id}", name="get_one")
     *
     * @param Cart $cart
     *
     * @return JsonResponse
     */
    public function getOne(Cart $cart): JsonResponse
    {
        $data = $this->serializer->serialize($cart, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Post("/carts", name="create")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $items = $content['items'] ?? null;

        $cart = new Cart();

        if ($items && is_array($items)) {
            foreach ($items as $item) {
                if (!isset($item['productId']) || !isset($item['quantity'])) {
                    $error = [
                        'message' => 'The fields productId and quantity must be defined'
                    ];

                    return new JsonResponse($error, Response::HTTP_BAD_REQUEST, []);
                }

                $product = $this->em->getRepository(Product::class)->find($item['productId']);

                if ($product) {
                    $itemEntity = (new Item())
                        ->setProduct($product)
                        ->setQuantity($item['quantity'])
                    ;

                    $cart->addItem($itemEntity);
                }
            }
        }

        $this->em->persist($cart);
        $this->em->flush();

        $data = $this->serializer->serialize($cart, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Rest\Put("/carts/{id}/add", name="add_item")
     *
     * @param Cart $cart
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addItem(Cart $cart, Request $request): JsonResponse
    {
        dump($request->getContent());
        dump($request);
        $content = json_decode($request->getContent(), true);
        dump($content);
        $item = $content['item'] ?? null;

        if (null === $item) {
            $error = [
                'message' => 'The field item cannot be null'
            ];
            dump($error);

            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, []);
        }

        if (!isset($item['productId']) || !isset($item['quantity'])) {
            $error = [
                'message' => 'The fields productId and quantity must be defined'
            ];

            dump($error);
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, []);
        }

        $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $item['productId']]);

        if ($product) {
            $existingItem = null;
            foreach ($cart->getItems() as $itemEntity) {
                if ($itemEntity->getProduct()->getId() === $product->getId()) {
                    $existingItem = $itemEntity;
                    break;
                }
            }

            if ($existingItem) {
                $existingItem->setQuantity($existingItem->getQuantity() + $item['quantity']);
            } else {
                $newItem = (new Item())
                    ->setProduct($product)
                    ->setQuantity($item['quantity'])
                ;

                $cart->addItem($newItem);
            }

            $this->em->flush();
        }

        $data = $this->serializer->serialize($cart, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Put("/carts/{id}/remove", name="remove_item")
     *
     * @param Cart $cart
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeItem(Cart $cart, Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $itemId = $content['itemId'] ?? null;

        if (null !== $itemId) {
            $item = $this->em->getRepository(Item::class)->findOneBy(['id' => $itemId]);

            if ($item) {
                $cart->removeItem($item);
                $this->em->flush();
            }
        }

        $data = $this->serializer->serialize($cart, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Delete("/carts/{id}", name="delete")
     *
     * @param Cart $cart
     *
     * @return JsonResponse
     */
    public function delete(Cart $cart): JsonResponse
    {
        $this->em->remove($cart);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT, []);
    }

    /**
     * @Rest\Put("/carts/{id}/checkout", name="checkout")
     *
     * @param Cart $cart
     *
     * @return JsonResponse
     */
    public function checkout(Cart $cart): JsonResponse
    {
        if ($cart->isPaid()) {
            $error = [
                'message' => 'The cart is already paid'
            ];

            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, []);
        }

        $cart->setIsPaid(true);
        $this->em->flush();

        $data = $this->serializer->serialize($cart, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
