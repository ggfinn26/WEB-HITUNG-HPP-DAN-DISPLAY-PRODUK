<?php
namespace App;

class ProductImage {
    private int $id;
    private int $productId;
    private string $imageUrl;
    private bool $isPrimary;
    private \DateTime $createdAt;

    public function __construct(int $id, int $productId, string $imageUrl, bool $isPrimary, \DateTime $createdAt) {
        $this->id = $id;
        $this->productId = $productId;
        $this->imageUrl = $imageUrl;
        $this->isPrimary = $isPrimary;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getProductId(): int { return $this->productId; }
    public function getImageUrl(): string { return $this->imageUrl; }
    public function isPrimary(): bool { return $this->isPrimary; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'image_url' => $this->imageUrl,
            'is_primary' => $this->isPrimary,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}
