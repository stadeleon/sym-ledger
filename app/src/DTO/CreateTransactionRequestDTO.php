<?php

namespace App\DTO;

use App\Enum\CurrencyEnum;
use App\Validator\LedgerExists;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTransactionRequestDTO
{
    #[Assert\NotBlank(message: "Ledger id is required")]
    #[Assert\Uuid(message: "Ledger id must be a valid UUID")]
    #[LedgerExists]
    public ?string $ledgerId = null;

    #[Assert\NotBlank(message: "Transaction type is required")]
    #[Assert\Choice(choices: ['debit', 'credit'], message: "Type must be either 'debit' or 'credit'")]
    public ?string $type = null;

    #[Assert\NotBlank(message: "Amount is required")]
    #[Assert\Type(type: "numeric", message: "Amount must be a number")]
    public ?float $amount = null;

    #[Assert\NotBlank(message: "Currency is required")]
    #[Assert\Choice(choices: CurrencyEnum::ALLOWED_VALUES, message: "Currency must be one of {{ choices }}")]
    public ?string $currency = null;
}