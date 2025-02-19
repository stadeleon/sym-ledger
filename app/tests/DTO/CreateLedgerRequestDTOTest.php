<?php

namespace App\Tests\DTO;

use App\DTO\CreateLedgerRequestDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateLedgerRequestDTOTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidDTO(): void
    {
        $dto = new CreateLedgerRequestDTO();
        $dto->initialCurrency = 'USD';

        $errors = $this->validator->validate($dto);

        $this->assertCount(0, $errors, 'The DTO is valid if initialCurrency is USD');
    }

    public function testInvalidDTOBlank(): void
    {
        $dto = new CreateLedgerRequestDTO();
        $dto->initialCurrency = '';

        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, $this->count($errors), 'The DTO is invalid if initialCurrency is empty');
    }

    public function testInvalidDTOWrongValue(): void
    {
        $dto = new CreateLedgerRequestDTO();
        $dto->initialCurrency = 'ZZZ';

        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, $this->count($errors), 'The DTO is invalid if initialCurrency not in allowed list');
    }
}
