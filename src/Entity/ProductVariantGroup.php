<?php
namespace App;

class ProductVariantGroup {
    private int $id;
    private int $productId;
    private string $name;
    private \DateTime $createdAt;

    public function __construct(int $id, int $productId, string $name, \DateTime $createdAt) {
        $this->id = $id;
        $this->productId = $productId;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getProductId(): int { return $this->productId; }
    public function getName(): string { return $this->name; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}
