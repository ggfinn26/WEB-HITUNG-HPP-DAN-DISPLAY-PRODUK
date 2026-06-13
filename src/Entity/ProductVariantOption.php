<?php
namespace App;

class ProductVariantOption {
    private int $id;
    private int $groupId;
    private string $name;
    private \DateTime $createdAt;

    public function __construct(int $id, int $groupId, string $name, \DateTime $createdAt) {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getGroupId(): int { return $this->groupId; }
    public function getName(): string { return $this->name; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'group_id' => $this->groupId,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}
