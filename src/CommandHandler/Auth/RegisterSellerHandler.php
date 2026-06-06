<?php

namespace App\CommandHandler\Auth;

use App\Command\Auth\RegisterSellerCommand;
use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler(bus: 'command.bus')]
class RegisterSellerHandler
{
    public function __construct(
        private EntityManagerInterface      $em,
        private UserPasswordHasherInterface $hasher
    ) {}

    public function __invoke(RegisterSellerCommand $command): int
    {
        $seller = new Seller();
        $seller->setName($command->name);
        $seller->setEmail($command->email);
        $seller->setRoles(['ROLE_SELLER']);
        $seller->setPassword(
            $this->hasher->hashPassword($seller, $command->password)
        );

        $this->em->persist($seller);
        $this->em->flush();

        return $seller->getId();
    }
}