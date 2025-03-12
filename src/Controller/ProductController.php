<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


#[Route('/api/products')]
class ProductController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/add', name: 'add_product', methods: ['POST'])]
    public function addProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['description'], $data['price'], $data['stock'], $data['image_url'], $data['is_active'])) {
            return $this->json(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        $product = $this->productService->createProduct(
            $data['name'],
            $data['description'],
            (float)$data['price'],
            (int)$data['stock'],
            $data['image_url'],
            (bool)$data['is_active']
        );

        return $this->json([
            'message' => 'Product added successfully',
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'stock' => $product->getStock(),
                'image_url' => $product->getImageUrl(),
                'is_active' => $product->isActive()
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteProduct(int $id): JsonResponse
    {
        $result = $this->productService->deleteProduct($id);

        if (!$result) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['message' => 'Product deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/get/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
            'image_url' => $product->getImageUrl(),
            'is_active' => $product->isActive()
        ], Response::HTTP_OK);
    }
}
