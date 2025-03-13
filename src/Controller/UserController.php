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

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (
            !isset($data['email'], $data['password'], $data['first_name'], $data['last_name'], $data ['phone'])
        ) {
            return new JsonResponse([
                'error' => 'Missing email, password, first_name, last_name or phone'
            ], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);
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

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function getAuthenticatedUser(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'first_name'    => $user->getFirstName(),
            'last_name'     => $user->getLastName(),
            'id'            => $user->getId(),
            'email'         => $user->getEmail(),
            'phone'         => $user->getPhone(),
            'roles'         => $user->getRoles(),
        ], Response::HTTP_OK);
    }
}
