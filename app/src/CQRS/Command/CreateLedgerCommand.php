<?php

namespace App\CQRS\Command;

readonly class CreateLedgerCommand
{
    public function __construct(
        public string $initialCurrency
    ) {
    }
}