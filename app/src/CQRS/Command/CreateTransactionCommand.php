<?php

namespace App\CQRS\Command;

readonly class CreateTransactionCommand
{
    public function __construct(
        public string $ledgerId,
        public string $type,
        public float  $amount,
        public string $currency
    ) {}
}