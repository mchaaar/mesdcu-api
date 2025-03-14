<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

#[Route('/api')]
class DashboardController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/dashboard",
     *     summary="Obtenir le dashboard",
     *     description="Retourne la liste des produits actifs avec un booléen indiquant si l'utilisateur connecté est abonné à chaque produit.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits avec statut de souscription",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Produit A"),
     *                 @OA\Property(property="description", type="string", example="Description du produit A"),
     *                 @OA\Property(property="price", type="number", format="float", example=9.99),
     *                 @OA\Property(property="stock", type="integer", example=50),
     *                 @OA\Property(property="isActive", type="boolean", example=true),
     *                 @OA\Property(property="subscribed", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function getDashboard(ProductRepository $productRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user || !$user instanceof User) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $products = $productRepository->findAllByUserWithSubscription($user->getId());

        return $this->json($products);
    }
}
