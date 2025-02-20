<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class LedgerExists extends Constraint
{
    public string $message = 'Ledger with id "{{ ledgerId }}" does not exist';

    public function validatedBy()
    {
        return static::class.'Validator';
    }
}