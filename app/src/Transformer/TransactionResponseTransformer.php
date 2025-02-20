<?php

namespace App\Transformer;

use App\DTO\TransactionResponseDTO;
use App\Entity\Transaction;

class TransactionResponseTransformer
{
    public function transform(Transaction $transaction): TransactionResponseDTO
    {
        return new TransactionResponseDTO(
            id: $transaction->getId(),
            ledgerId: $transaction->getLedger()->getId(),
            type: $transaction->getType(),
            amount: $transaction->getAmount(),
            currency: $transaction->getCurrency()->value,
            createdAt: $transaction->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }
}