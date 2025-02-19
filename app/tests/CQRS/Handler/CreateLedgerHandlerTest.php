<?php

namespace App\Tests\CQRS\Handler;

use App\CQRS\Command\CreateLedgerCommand;
use App\CQRS\Handler\CreateLedgerHandler;
use App\Entity\Ledger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CreateLedgerHandlerTest extends TestCase
{
    public function testHandlerCreateLedger(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Ledger::class));

        $em->expects($this->once())->method('flush');

        $handler = new CreateLedgerHandler($em, $logger);
        $command = new CreateLedgerCommand('USD');
        $ledger = $handler->handle($command);

        $this->assertInstanceOf(Ledger::class, $ledger);
        $this->assertSame('USD', $ledger->getInitialCurrency()->value);
    }
}
