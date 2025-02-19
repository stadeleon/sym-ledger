<?php

namespace App\CQRS\Handler;

use App\CQRS\Command\CreateLedgerCommand;
use App\Entity\Ledger;
use App\Enum\CurrencyEnum;
use Doctrine\ORM\EntityManagerInterface;

class CreateLedgerHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(CreateLedgerCommand $command): Ledger
    {
        $ledger = new Ledger();
        $ledger->setInitialCurrency(CurrencyEnum::from($command->initialCurrency));
        $ledger->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($ledger);
        $this->entityManager->flush();

        return $ledger;
    }
}