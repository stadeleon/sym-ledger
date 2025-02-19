<?php

namespace App\DTO;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateLedgerRequestDTO
{
    #[Assert\NotBlank(message: 'Initial currency is required')]
    #[Assert\Choice(choices: CurrencyEnum::ALLOWED_VALUES, message: 'Initial currency must be one of {{ choices }}')]
    public ?string $initialCurrency = null;
}