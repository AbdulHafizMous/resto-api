<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "App\Repository\ReservationRepository")]
#[ORM\Table(name: "reservations")]
class Reservation
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    #[Groups(["list", "details"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[Groups(["list", "details"])]
    private string $customerName;

    #[ORM\Column(type: "string", length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(["details"])]
    private string $customerEmail;

    #[ORM\Column(type: "string", length: 15)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 15)]
    #[Groups(["details"])]
    private string $customerPhone;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank]
    #[Groups(["list", "details"])]
    private \DateTimeInterface $date;

    #[ORM\Column(type: "time")]
    #[Assert\NotBlank]
    #[Groups(["list", "details"])]
    private \DateTimeInterface $time;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 20)]
    #[Groups(["list", "details"])]
    private int $numberOfGuests;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["details"])]
    private ?string $specialRequests = null;

    #[ORM\Column(type: "string", length: 20)]
    #[Assert\Choice(choices: ["pending", "confirmed", "cancelled"])]
    #[Groups(["list", "details"])]
    private string $status = 'pending';

    #[ORM\Column(type: "datetime")]
    #[Groups(["details"])]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function getCustomerPhone(): string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getTime(): \DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getNumberOfGuests(): int
    {
        return $this->numberOfGuests;
    }

    public function setNumberOfGuests(int $numberOfGuests): self
    {
        $this->numberOfGuests = $numberOfGuests;
        return $this;
    }

    public function getSpecialRequests(): ?string
    {
        return $this->specialRequests;
    }

    public function setSpecialRequests(?string $specialRequests): self
    {
        $this->specialRequests = $specialRequests;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
