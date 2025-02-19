<?php

namespace App\CQRS\Command;

class CreateLedgerCommand
{
    public function __construct(
        public readonly string $initialCurrency
    ) {
    }
}