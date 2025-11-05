<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table(name: 'payments')]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[Gedmo\SoftDeleteable]
#[Gedmo\Loggable]
class Payment
{
    use TimestampableEntity, SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 32)]
    private ?string $method = null;

    #[ORM\Column(length: 255)]
    private ?string $notes = null;

    /**
     * @var Collection<int, OrderPayment>
     */
    #[ORM\OneToMany(targetEntity: OrderPayment::class, mappedBy: 'payment')]
    private Collection $orderPayments;

    public function __construct()
    {
        $this->orderPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, OrderPayment>
     */
    public function getOrderPayments(): Collection
    {
        return $this->orderPayments;
    }

    public function addOrderPayment(OrderPayment $orderPayment): static
    {
        if (!$this->orderPayments->contains($orderPayment)) {
            $this->orderPayments->add($orderPayment);
            $orderPayment->setPayment($this);
        }

        return $this;
    }

    public function removeOrderPayment(OrderPayment $orderPayment): static
    {
        if ($this->orderPayments->removeElement($orderPayment)) {
            // set the owning side to null (unless already changed)
            if ($orderPayment->getPayment() === $this) {
                $orderPayment->setPayment(null);
            }
        }

        return $this;
    }
}
