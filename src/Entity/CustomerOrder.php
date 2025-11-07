<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "App\Repository\CustomerOrderRepository")]
#[ORM\Table(name: "orders")]
class CustomerOrder
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    #[Groups(["list","details"])]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    #[Assert\Range(min:1, max:50)]
    #[Groups(["list","details"])]
    private int $tableNumber;

    #[ORM\Column(type: "json")]
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    #[Groups(["details"])]
    private array $items = [];

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    #[Groups(["list","details"])]
    private string $totalAmount = '0.00';

    #[ORM\Column(type: "string", length: 20)]
    #[Assert\Choice(choices: ["pending","preparing","ready","delivered"])]
    #[Groups(["list","details"])]
    private string $status = 'pending';

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["details"])]
    private ?string $customerNote = null;

    #[ORM\Column(type: "datetime")]
    #[Groups(["list","details"])]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getTableNumber(): int { return $this->tableNumber; }
    public function setTableNumber(int $n): self { $this->tableNumber = $n; return $this; }
    public function getItems(): array { return $this->items; }
    public function setItems(array $items): self { $this->items = $items; return $this; }
    public function getTotalAmount(): string { return $this->totalAmount; }
    public function setTotalAmount(string $amount): self { $this->totalAmount = $amount; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }
    public function getCustomerNote(): ?string { return $this->customerNote; }
    public function setCustomerNote(?string $n): self { $this->customerNote = $n; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}
