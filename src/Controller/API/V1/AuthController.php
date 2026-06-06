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
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Controller\API\V1\Model\Auth\Request\RegisterSellerRequest;
use App\Controller\API\V1\Model\Auth\Response\RegisterSellerResponse;
use App\Command\Auth\RegisterSellerCommand;
use OpenApi\Attributes as OA;

class AuthController extends AbstractController
{
    //public function __construct(private ValidatorInterface $validator) {}

    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/register',
        summary: 'Register a new seller',
        description: 'Creates a new seller account and returns the seller details'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/RegisterSellerRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Seller registered successfully',
        content: new OA\JsonContent(ref: '#/components/schemas/RegisterSellerResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'title',   type: 'string', example: 'An error occurred'),
                new OA\Property(property: 'detail',  type: 'string', example: 'email: Invalid email format'),
                new OA\Property(
                    property: 'violations',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'propertyPath', type: 'string', example: 'email'),
                            new OA\Property(property: 'title',        type: 'string', example: 'Invalid email format')
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 409,
        description: 'Email already exists',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'This email is already registered')
            ]
        )
    )]
    public function register(
        #[MapRequestPayload] RegisterSellerRequest $request
    ): JsonResponse {

        $this->commandBus->dispatch(new RegisterSellerCommand(
            name:     $request->name,
            email:    $request->email,
            password: $request->password
        ));

        return $this->json(
            (new RegisterSellerResponse(
                message:   'Seller registered successfully'
            ))->toArray(),
            201
        );
    }
}