<?php

namespace App\Validator;

use App\Repository\LedgerRepository;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LedgerExistsValidator extends ConstraintValidator
{

    public function __construct(private readonly LedgerRepository $ledgerRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof LedgerExists) {
            throw new UnexpectedTypeException($constraint, LedgerExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            $uuid = Uuid::fromString($value);
        } catch (InvalidArgumentException $e) {
            $this->context->buildViolation('The ledgerId "{{ value }}" is not a valid UUID.')
                ->setParameter('{{ value }}', $value)
                ->addViolation();
            return;
        }

        $ledger = $this->ledgerRepository->find($value);
        if (null === $ledger) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ ledgerId }}', $value)
                ->addViolation();
        }
    }
}