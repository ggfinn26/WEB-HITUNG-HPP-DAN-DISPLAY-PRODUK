<?php
namespace App;

class OrderSesi {
    private int $id;
    private int $sesiId;
    private int $orderId;
    private string $orderNumber;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        int $id, int $sesiId, int $orderId, string $orderNumber,
        \DateTime $createdAt, \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->sesiId = $sesiId;
        $this->orderId = $orderId;
        $this->orderNumber = $orderNumber;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int { return $this->id; }
    public function getSesiId(): int { return $this->sesiId; }
    public function getOrderId(): int { return $this->orderId; }
    public function getOrderNumber(): string { return $this->orderNumber; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }
}
