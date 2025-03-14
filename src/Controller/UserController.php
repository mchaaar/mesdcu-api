<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

#[Route('/api')]
class UserController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Creates a new user by providing email, password, first_name, last_name and phone.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "first_name", "last_name", "phone"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="0123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing parameters"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="User already exists"
     *     )
     * )
     */
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'], $data['first_name'], $data['last_name'], $data['phone'])) {
            return new JsonResponse(
                ['error' => 'Missing email, password, first_name, last_name or phone'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $user->setRoles(array_unique([...$user->getRoles(), 'ROLE_USER']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get authenticated user details",
     *     description="Returns information about the currently authenticated user.",
     *     @OA\Response(
     *         response=200,
     *         description="User details returned",
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"ROLE_USER"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not authenticated"
     *     )
     * )
     * @Security(name="Bearer")
     */
    #[Route('/me', name: 'me', methods: ['GET'])]
    public function getAuthenticatedUser(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'id'         => $user->getId(),
            'email'      => $user->getEmail(),
            'phone'      => $user->getPhone(),
            'roles'      => $user->getRoles(),
        ], Response::HTTP_OK);
    }
}
