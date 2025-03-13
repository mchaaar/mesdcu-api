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

#[Route('/api/subscriptions')]
class SubscriptionController extends AbstractController
{
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
