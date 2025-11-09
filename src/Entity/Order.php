<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table('orders')]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[Gedmo\SoftDeleteable]
#[Gedmo\Loggable]
class Order
{
    use TimestampableEntity, SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[Gedmo\Versioned]
    #[ORM\Column(type: Types::STRING, length: 32, enumType: OrderStatus::class)]
    private ?OrderStatus $status = null;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderProducts;

    /**
     * @var Collection<int, OrderPayment>
     */
    #[ORM\OneToMany(targetEntity: OrderPayment::class, mappedBy: 'order')]
    private Collection $orderPayments;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
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

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            if ($orderProduct->getOrder() === $this) {
                $orderProduct->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderPayment>
     */
    public function getOrderPayments(): Collection
    {
        return $this->orderPayments;
    }

    public function getTotalPaid(): string
    {
        return $this->getOrderPayments()->reduce(
            fn(string $total, OrderPayment $p) => bcadd($total, $p->getAmountApplied(), 2),
            '0.00'
        );
    }

    public function getRemainingAmount(): string
    {
        return bcsub($this->getTotal(), $this->getTotalPaid(), 2);
    }

    public function addOrderPayment(OrderPayment $orderPayment): static
    {
        if (!$this->orderPayments->contains($orderPayment)) {
            $this->orderPayments->add($orderPayment);
            $orderPayment->setOrder($this);
        }

        return $this;
    }

    public function removeOrderPayment(OrderPayment $orderPayment): static
    {
        if ($this->orderPayments->removeElement($orderPayment)) {
            // set the owning side to null (unless already changed)
            if ($orderPayment->getOrder() === $this) {
                $orderPayment->setOrder(null);
            }
        }

        return $this;
    }
}
