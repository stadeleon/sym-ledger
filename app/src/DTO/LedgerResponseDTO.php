<?php

namespace App\DTO;

class LedgerResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $initialCurrency,
        public readonly string $createdAt
    ) {}
}