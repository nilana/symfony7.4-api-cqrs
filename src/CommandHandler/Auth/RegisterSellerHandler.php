<?php

namespace App\CommandHandler\Auth;

use App\Command\Auth\RegisterSellerCommand;
use App\Entity\Seller;
use App\Exception\Seller\SellerExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterSellerHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {}

    public function __invoke(RegisterSellerCommand $command): array
    {
        $existing = $this->em->getRepository(Seller::class)
            ->findOneBy(['email' => $command->email]);

        if ($existing) {
            throw SellerExceptionFactory::emailAlreadyExists();
        }

        $seller = new Seller();
        $seller->setName($command->name);
        $seller->setEmail($command->email);
        $seller->setRoles(['ROLE_SELLER']);
        $seller->setPassword(
            $this->hasher->hashPassword($seller, $command->password)
        );

        $this->em->persist($seller);
        $this->em->flush();

        return ['id'=>$seller->getId()];
    }
}