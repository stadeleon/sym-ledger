<?php

namespace App\CQRS\Handler;

use App\CQRS\Command\CreateLedgerCommand;
use App\Entity\Ledger;
use App\Enum\CurrencyEnum;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class CreateLedgerHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function handle(CreateLedgerCommand $command): Ledger
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        try {
            $ledger = new Ledger();
            $ledger->setInitialCurrency(CurrencyEnum::from($command->initialCurrency));
            $ledger->setCreatedAt(new DateTimeImmutable());

            $this->entityManager->persist($ledger);
            $this->entityManager->flush();

            $connection->commit();

            return $ledger;
        } catch (Exception $e) {
            $connection->rollBack();

            $this->logger->error('Error creating ledger', [
                'exception' => $e,
                'command' => $command
            ]);

            throw $e;
        }
    }
}