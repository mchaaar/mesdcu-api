<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

#[Route('/api/subscriptions')]
class SubscriptionController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/subscriptions/add",
     *     summary="Create a subscription",
     *     description="Creates a new subscription linking a user and a product.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId", "productId"},
     *             @OA\Property(property="userId", type="integer", example=1),
     *             @OA\Property(property="productId", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subscription created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subscription created successfully"),
     *             @OA\Property(property="subscriptionId", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing userId or productId"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User or Product not found"
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/add', name: 'subscription_add', methods: ['POST'])]
    public function addSubscription(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userId    = $data['userId'] ?? null;
        $productId = $data['productId'] ?? null;

        if (!$userId || !$productId) {
            return $this->json(['error' => 'Missing userId or productId'], Response::HTTP_BAD_REQUEST);
        }

        $user = $em->getRepository(User::class)->find($userId);
        $product = $em->getRepository(Product::class)->find($productId);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        if (!$product) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setProduct($product);

        $em->persist($subscription);
        $em->flush();

        return $this->json([
            'message' => 'Subscription created successfully',
            'subscriptionId' => $subscription->getId()
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     path="/api/subscriptions/remove",
     *     summary="Remove a subscription",
     *     description="Removes a subscription linking a user and a product.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId", "productId"},
     *             @OA\Property(property="userId", type="integer", example=1),
     *             @OA\Property(property="productId", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Subscription removed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing userId or productId"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No subscription found for given userId/productId"
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/remove', name: 'subscription_remove', methods: ['DELETE'])]
    public function removeSubscription(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userId    = $data['userId'] ?? null;
        $productId = $data['productId'] ?? null;

        if (!$userId || !$productId) {
            return $this->json(['error' => 'Missing userId or productId'], Response::HTTP_BAD_REQUEST);
        }

        $subscription = $em->getRepository(Subscription::class)->findOneBy([
            'user'    => $userId,
            'product' => $productId,
        ]);

        if (!$subscription) {
            return $this->json(['error' => 'No subscription found for given userId/productId'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($subscription);
        $em->flush();

        return $this->json([
            'message' => 'Subscription removed successfully'
        ], Response::HTTP_OK);
    }
}
