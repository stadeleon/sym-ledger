<?php

namespace App\Entity;

use App\Repository\LedgerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: LedgerRepository::class)]
class Ledger
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column(length: 3)]
    private ?string $initialCurrency = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'ledger')]
    private Collection $transactions;

    /**
     * @var Collection<int, LedgerBalance>
     */
    #[ORM\OneToMany(targetEntity: LedgerBalance::class, mappedBy: 'ledger')]
    private Collection $ledgerBalances;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->ledgerBalances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getInitialCurrency(): ?string
    {
        return $this->initialCurrency;
    }

    public function setInitialCurrency(string $initialCurrency): static
    {
        $this->initialCurrency = $initialCurrency;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setLedger($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getLedger() === $this) {
                $transaction->setLedger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LedgerBalance>
     */
    public function getLedgerBalances(): Collection
    {
        return $this->ledgerBalances;
    }

    public function addLedgerBalance(LedgerBalance $ledgerBalance): static
    {
        if (!$this->ledgerBalances->contains($ledgerBalance)) {
            $this->ledgerBalances->add($ledgerBalance);
            $ledgerBalance->setLedger($this);
        }

        return $this;
    }

    public function removeLedgerBalance(LedgerBalance $ledgerBalance): static
    {
        if ($this->ledgerBalances->removeElement($ledgerBalance)) {
            // set the owning side to null (unless already changed)
            if ($ledgerBalance->getLedger() === $this) {
                $ledgerBalance->setLedger(null);
            }
        }

        return $this;
    }
}
