<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_READY = 'ready';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $reference = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 50)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 50)]
    private string $paymentStatus = self::PAYMENT_PENDING;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalAmount = null;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2, nullable: true)]
    private ?string $deliveryFee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $deliveryPostalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $deliveryCity = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $customerEmail = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $customerPhone = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->reference = $this->generateReference();
    }

    private function generateReference(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getDeliveryFee(): ?string
    {
        return $this->deliveryFee;
    }

    public function setDeliveryFee(?string $deliveryFee): static
    {
        $this->deliveryFee = $deliveryFee;
        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    public function getDeliveryPostalCode(): ?string
    {
        return $this->deliveryPostalCode;
    }

    public function setDeliveryPostalCode(?string $deliveryPostalCode): static
    {
        $this->deliveryPostalCode = $deliveryPostalCode;
        return $this;
    }

    public function getDeliveryCity(): ?string
    {
        return $this->deliveryCity;
    }

    public function setDeliveryCity(?string $deliveryCity): static
    {
        $this->deliveryCity = $deliveryCity;
        return $this;
    }

    public function getFullDeliveryAddress(): string
    {
        return sprintf('%s, %s %s', $this->deliveryAddress, $this->deliveryPostalCode, $this->deliveryCity);
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): static
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(?string $customerPhone): static
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }
        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }
        return $this;
    }

    /**
     * Calcule le sous-total des articles (sans frais de livraison)
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item->getSubtotal();
        }
        return $subtotal;
    }

    /**
     * Calcule et met à jour le total
     */
    public function calculateTotal(): static
    {
        $total = $this->getSubtotal() + (float) ($this->deliveryFee ?? 0);
        $this->totalAmount = (string) $total;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTimeImmutable $deliveredAt): static
    {
        $this->deliveredAt = $deliveredAt;
        return $this;
    }

    /**
     * Retourne le label du statut
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_PREPARING => 'En préparation',
            self::STATUS_READY => 'Prête',
            self::STATUS_DELIVERING => 'En livraison',
            self::STATUS_DELIVERED => 'Livrée',
            self::STATUS_CANCELLED => 'Annulée',
            default => 'Inconnu',
        };
    }

    /**
     * Retourne le label du statut de paiement
     */
    public function getPaymentStatusLabel(): string
    {
        return match ($this->paymentStatus) {
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PAID => 'Payé',
            self::PAYMENT_FAILED => 'Échoué',
            self::PAYMENT_REFUNDED => 'Remboursé',
            default => 'Inconnu',
        };
    }
}
