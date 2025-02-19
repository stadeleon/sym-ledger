<?php

namespace App\Transformer;

use App\DTO\LedgerResponseDTO;
use App\Entity\Ledger;

class LedgerResponseTransformer
{
    public function transform(Ledger $ledger): LedgerResponseDTO
    {
        return new LedgerResponseDTO(
            $ledger->getId(),
            $ledger->getInitialCurrency()->value,
            $ledger->getCreatedAt()->format('Y-m-d H:i:s')
        );
    }
}