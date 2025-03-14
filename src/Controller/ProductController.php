<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Post(
     *     path="/api/products/add",
     *     summary="Add a new product",
     *     description="Creates a new product with the provided details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "stock", "image_url", "is_active"},
     *             @OA\Property(property="name", type="string", example="My Product"),
     *             @OA\Property(property="description", type="string", example="Product description"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product added successfully"),
     *             @OA\Property(property="product", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="My Product"),
     *                 @OA\Property(property="price", type="number", format="float", example=19.99),
     *                 @OA\Property(property="stock", type="integer", example=100),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing parameters"
     *     )
     * )
     * @Security(name="Bearer")
     */
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
            (float) $data['price'],
            (int) $data['stock'],
            $data['image_url'],
            (bool) $data['is_active']
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

    /**
     * @OA\Delete(
     *     path="/api/products/delete/{id}",
     *     summary="Delete a product",
     *     description="Deletes a product by its id.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteProduct(int $id): JsonResponse
    {
        $result = $this->productService->deleteProduct($id);

        if (!$result) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['message' => 'Product deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/products/get/{id}",
     *     summary="Get a product by id",
     *     description="Returns the product details for the specified id.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product found",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="My Product"),
     *             @OA\Property(property="description", type="string", example="Product description"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     * @Security(name="Bearer")
     */
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

    /**
     * @OA\Get(
     *     path="/api/products/all",
     *     summary="Get all products",
     *     description="Returns a list of all products.",
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="My Product"),
     *                 @OA\Property(property="description", type="string", example="Product description"),
     *                 @OA\Property(property="price", type="number", format="float", example=19.99),
     *                 @OA\Property(property="stock", type="integer", example=100),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/all', name: 'get_all_products', methods: ['GET'])]
    public function getAllProducts(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = [
                'id'          => $product->getId(),
                'name'        => $product->getName(),
                'description' => $product->getDescription(),
                'price'       => $product->getPrice(),
                'stock'       => $product->getStock(),
                'image_url'   => $product->getImageUrl(),
                'is_active'   => $product->isActive(),
            ];
        }

        return $this->json($productsData);
    }
}
