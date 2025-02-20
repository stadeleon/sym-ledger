<?php

namespace App\DTO;

class TransactionResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $ledgerId,
        public readonly string $type,
        public readonly float  $amount,
        public readonly string $currency,
        public readonly string $createdAt
    ) {
    }
}