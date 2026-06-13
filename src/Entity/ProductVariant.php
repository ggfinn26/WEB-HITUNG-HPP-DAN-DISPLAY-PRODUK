<?php
namespace App;

class ProductVariant {
    private int $id;
    private int $productId;
    private string $name;
    private ?string $sku;
    private ?string $price;
    private int $stock;
    private ?int $imageId;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;
    
    // Virtual property for storing related option IDs (from product_variant_combinations)
    private array $optionIds = [];

    public function __construct(int $id, int $productId, string $name, ?string $sku, ?string $price, int $stock, ?int $imageId, \DateTime $createdAt, \DateTime $updatedAt) {
        $this->id = $id;
        $this->productId = $productId;
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
        $this->stock = $stock;
        $this->imageId = $imageId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int { return $this->id; }
    public function getProductId(): int { return $this->productId; }
    public function getName(): string { return $this->name; }
    public function getSku(): ?string { return $this->sku; }
    public function getPrice(): ?string { return $this->price; }
    public function getStock(): int { return $this->stock; }
    public function getImageId(): ?int { return $this->imageId; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }

    public function getOptionIds(): array { return $this->optionIds; }
    public function setOptionIds(array $optionIds): void { $this->optionIds = $optionIds; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'image_id' => $this->imageId,
            'option_ids' => $this->optionIds,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
}
