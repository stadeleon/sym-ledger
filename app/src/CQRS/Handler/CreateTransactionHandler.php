<?php

namespace App\CQRS\Handler;

use App\CQRS\Command\CreateTransactionCommand;
use App\Entity\Transaction;
use App\Enum\CurrencyEnum;
use App\Repository\LedgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CreateTransactionHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LedgerRepository $ledgerRepository,
        private LoggerInterface $logger
    ) {
    }

    public function handle(CreateTransactionCommand $command): Transaction
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        try {
            $ledger = $this->ledgerRepository->find($command->ledgerId);
            if (!$ledger) {
                throw new \RuntimeException("Ledger not found with id: " . $command->ledgerId);
            }

            $transaction = new Transaction();
            $transaction->setLedger($ledger);
            $transaction->setType($command->type);
            $transaction->setAmount($command->amount);
            $transaction->setCurrency(CurrencyEnum::from($command->currency));
            $transaction->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($transaction);
            $this->entityManager->flush();
            $connection->commit();

            return $transaction;
        } catch (\Exception $e) {
            $connection->rollBack();
            $this->logger->error('Error creating transaction', [
                'exception' => $e,
                'command' => $command
            ]);

            throw $e;
        }
    }

}