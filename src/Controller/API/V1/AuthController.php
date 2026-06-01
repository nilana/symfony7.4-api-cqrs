<?php

namespace App\Controller\API\V1;

use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator) {}

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

        $errors = $this->validator->validate($seller);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $messages], 422);
        }

        $em->persist($seller);
        $em->flush();

        return $this->json(['message' => 'Seller registered successfully'], 201);
    }
}