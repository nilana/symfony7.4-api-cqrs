<?php

namespace App\Controller\API\V1\Model\Auth\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'RegisterSellerRequest',
    required: ['name', 'email', 'password'],
    description: 'Request payload for seller registration'
)]
class RegisterSellerRequest
{
    public function __construct(
        #[OA\Property(
            description: 'Full name of the seller',
            type: 'string',
            example: 'John Doe'
        )]
        #[Assert\NotBlank(message: 'Name is required')]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Name must be at least {{ limit }} characters',
            maxMessage: 'Name cannot exceed {{ limit }} characters'
        )]
        public readonly string $name,

        #[OA\Property(
            description: 'Email address of the seller',
            type: 'string',
            format: 'email',
            example: 'john@example.com'
        )]
        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'Invalid email format')]
        public readonly string $email,

        #[OA\Property(
            description: 'Password for the seller account',
            type: 'string',
            format: 'password',
            example: 'secret123'
        )]
        #[Assert\NotBlank(message: 'Password is required')]
        #[Assert\Length(
            min: 8,
            minMessage: 'Password must be at least {{ limit }} characters'
        )]
        public readonly string $password
    ) {}
}