<?php

namespace App\Controller\API\V1;

use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $seller = new Seller();
        $seller->setName($data['name']);
        $seller->setEmail($data['email']);
        $seller->setRoles(['ROLE_SELLER']);
        $seller->setPassword(
            $hasher->hashPassword($seller, $data['password'])
        );

        $em->persist($seller);
        $em->flush();

        return $this->json(['message' => 'Seller registered successfully'], 201);
    }
}