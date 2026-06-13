<?php
namespace App;

class SesiBobot {
    private int $id;
    private int $sesiId;
    private ?array $itemProporsional;
    private ?array $itemFlat;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        int $id, int $sesiId, ?array $itemProporsional, ?array $itemFlat,
        \DateTime $createdAt, \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->sesiId = $sesiId;
        $this->itemProporsional = $itemProporsional;
        $this->itemFlat = $itemFlat;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int { return $this->id; }
    public function getSesiId(): int { return $this->sesiId; }
    public function getItemProporsional(): ?array { return $this->itemProporsional; }
    public function getItemProporsionalJson(): ?string { return $this->itemProporsional ? json_encode($this->itemProporsional) : null; }
    public function setItemProporsional(?array $v): void { $this->itemProporsional = $v; }

    public function getItemFlat(): ?array { return $this->itemFlat; }
    public function getItemFlatJson(): ?string { return $this->itemFlat ? json_encode($this->itemFlat) : null; }
    public function setItemFlat(?array $v): void { $this->itemFlat = $v; }

    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }
}
