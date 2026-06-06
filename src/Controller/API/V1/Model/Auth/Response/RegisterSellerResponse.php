<?php

namespace App\Controller\API\V1\Model\Auth\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterSellerResponse',
    description: 'Response after successful seller registration'
)]
class RegisterSellerResponse
{
    public function __construct(
        #[OA\Property(
            description: 'Success message',
            type: 'string',
            example: 'Seller registered successfully'
        )]
        public readonly string $message,

    ) {}

    public function toArray(): array
    {
        return [
            'message'   => $this->message
        ];
    }
}